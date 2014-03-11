<?php
    /**
     * Class definition for Zendesk Service
     */
    namespace Malwarebytes\ZendeskBundle\Service;
    use \FOS\UserBundle\Model\UserManager;
    use CM\UserBundle\Entity\User as FOSUser;
    use Malwarebytes\ZendeskBundle\DataModel\Ticket\Entity as Ticket;
    use Malwarebytes\ZendeskBundle\DataModel\User\Entity as User;
    use Malwarebytes\ZendeskBundle\DataModel\Group\Entity as Group;
    /**
     * This class provides an API for some basic logic interacting with the Zendesk API through our Data Model
     * @author relwell
     */
    class ZendeskService
    {
        /**
         * Allows us to access any repository
         * @var RepositoryService
         */
        protected $_repos;

        /**
         * Generalized memoization cache
         * @var array
         */
        protected $_cache;

        protected $_lastEntityId;

        /** @var  \FOS\UserBundle\Model\UserManager $fos_user_manager */
        protected $fos_user_manager;

        protected $em;

        public function getLastEntityId()
        {
            return $this->_lastEntityId;
        }

        public function __construct( RepositoryService $repoService, UserManager $fos_user_manager, $em )
        {
            $this->_repos = $repoService;
            $this->fos_user_manager= $fos_user_manager;
            $this -> em = $em;
        }

        /**
         * Returns an array of tickets, with comments populated, for a user ID.
         * @param int $userId
         * @throws \Exception
         * @return array
         */
        public function getTicketsWithCommentsForUserId( $userId )
        {
            $user = $this->getById( 'User', $userId );
            $ticketRepo = $this->_repos->get( 'Ticket' );
            $tickets = $ticketRepo->getTicketsRequestedByUser( $user );
            foreach ( $tickets as $ticket ) {
                $ticket['comments'] = $this->getCommentsForTicket( $ticket );
            }
            return $tickets;
        }

        /**
         * Puts "comments" field in ticket
         * @param Ticket $ticket
         * @return array
         */
        public function getCommentsForTicket( Ticket $ticket )
        {
            return $this->_repos->get( 'Audit' )->getCommentsForTicket( $ticket );
        }

        /**
         * Creates a ticket requested by the provided user
         * @param int $userId
         * @param string $subject
         * @param string $comment
         */
        public function createTicketAsUser( $userId, $subject, $comment )
        {
            $user = $this->getById( 'User', $userId ); // validates our user
            $ticketRepo = $this->_repos->get( 'Ticket' );
            $ticket = $ticketRepo->factory();
            $ticket['requester_id'] = $user['id'];
            $ticket['subject'] = $subject;
            $ticket['comment'] = array( 'body' => $comment );
            $ticket['via']=array('channel'=>'api','source'=>'ticket_sharing');
            $response = $ticketRepo->save( $ticket );

            $this->_lastEntityId = $response['id'];

            return $this;
        }

        /**
         * Provided ticket ID, name, and email, adds user as collaborator to ticket.
         * @param int $ticketId
         * @param string $userName
         * @param string $userEmail
         * @return \Malwarebytes\ZendeskBundle\Service\ZendeskService
         */
        public function addCollaboratorToTicket( $ticketId, $userName, $userEmail )
        {
            $ticket = $this->getById( 'Ticket', $ticketId );
            $user = $this->_repos->get( 'User' )->getForNameAndEmail( $userName, $userEmail );
            if ( $user ) {
                $ticket->addCollaborator( $user );
            }
            return $this;
        }

        /**
         * Validates ticket and group ids, and then changes the group for the ticket.
         * @param int $ticketId
         * @param int $groupId
         * @return \Malwarebytes\ZendeskBundle\Service\ZendeskService
         */
        public function changeTicketGroup( $ticketId, $groupId )
        {
            $ticket = $this->getById( 'Ticket', $ticketId );
            $group = $this->getById( 'Group', $groupId );
            $ticket['group_id'] = $group['id'];
            $this->_repos->get( 'Ticket' )->save( $ticket );
            return $this;
        }

        /**
         * Provided an ID and a comment, adds a comment to a ticket and returns the entity.
         * @param int $ticketId
         * @param string $comment
         * @param bool $public
         * @return \Malwarebytes\ZendeskBundle\DataModel\Ticket\Entity
         */
        public function addCommentToTicket( $ticketId, $comment, $public = false )
        {
            $ticket = $this->getById( 'Ticket', $ticketId );
            $ticket->addComment( $comment, $public );
            return $ticket;
        }

        /**
         * Generalized get by ID with memoization cache
         * @param string $repoString
         * @param int $entityId
         * @return Malwarebytes\ZendeskBundle\DataModel\AbstractEntity
         */
        public function getById( $repoString, $entityId )
        {
            // initialize cache for this repo
            if (! isset( $this->_cache[$repoString] ) ) {
                $this->_cache[$repoString] = array();
            }
            if ( empty( $this->_cache[$repoString][$entityId] ) ) {
                $entity = $this->_repos->get( $repoString )->getById( $entityId );
                if ( empty( $entity ) ) {
                    throw new \Exception( "No entity for found for ID {$entityId} and repository \"{$repoString}\"" );
                }
                $this->_cache[$repoString][$entityId] = $entity;
            }
            return $this->_cache[$repoString][$entityId];
        }

        /**
         * @param $name
         * @param $email
         */
        public function getZendeskIDForVistor($name,$email)
        {
            $zendeskUser = $zendeskUser = $this -> _repos
                ->get('User')
                ->getForNameAndEmail(
                    $name,
                    $email,
                    true
                );
            return $zendeskUser -> _fields['id'];


        }
        public function getZenddeskIDForFOSUser(FOSUser $user)
        {

            if($user -> getZendeskID())
                return $user -> getZendeskID();
            else
            {
                //see if they've already emailed in perhaps
                $zendeskUser = $this -> _repos
                    ->get('User')
                    ->getForNameAndEmail(
                        $user -> getFullName(),
                        $user -> getEmail()
                    );

                if($zendeskUser)
                {
                    $user -> setZendeskID($zendeskUser -> _fields['id']);
                    $this -> fos_user_manager->updateUser($user);
                    return $user -> getZendeskID();
                }

                //we still need to create one

                $zendeskUser = $zendeskUser = $this -> _repos
                    ->get('User')
                    ->getForNameAndEmail(
                        $user -> getFullName(),
                        $user -> getEmail(),
                        true
                    );
                $user -> setZendeskID($zendeskUser -> _fields['id']);
                $this -> fos_user_manager->updateUser($user);
                return $user -> getZendeskID();

            }
        }
    }