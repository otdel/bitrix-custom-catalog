<?php

namespace Oip\GuestUser\Clearer;

use DateTime;
use Exception;

use Oip\GuestUser\Clearer\Entity\ProductView\Record as ProductView;
use Oip\GuestUser\Clearer\Repository\RepositoryInterface;
use Oip\GuestUser\Entity\User;

use Oip\GuestUser\Clearer\Exception\UserDoesntExist as UserDoesntExistException;
use Oip\GuestUser\Clearer\Exception\UserIsntExpired as UserIsntExpiredException;
use Oip\GuestUser\Clearer\Exception\ArchivingRecordError as ArchivingRecordErrorException;
use Oip\GuestUser\Clearer\Exception\DeletingExpiredError as DeletingExpiredErrorException;

class Handler {

    /** @var RepositoryInterface $repository */
    private $repository;

    public function __construct(RepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * @param int $guestId
     * @return User
     * @throws UserDoesntExistException
     * @throws UserIsntExpiredException
     * @throws Exception
     */
    public function getExpiredUser(int $guestId) {
        $user = $this->repository->getUserById($guestId);

        if(is_null($user)) {
            throw new UserDoesntExistException($guestId);
        }

        if(!$this->isUserExpired($user)) {
            throw new UserIsntExpiredException($guestId);
        }

        return $user;
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function isUserExpired(User $user): bool {
        $curDateTimestamp = (new DateTime())->getTimestamp();
        $lastVisitTimestamp = $user->getLasVisit()->getTimestamp();

        // условия смерти юзера - 3 месяца с момента последнего захода
        return (($curDateTimestamp - $lastVisitTimestamp)/60/60/24/30 > 3);
    }

    /**
     * @param User $user
     * @return ProductView[]
     */
    public function getUserProductViews(User $user) {
        return $this->repository->getUserProductViews($user->getNegativeId());
    }

    /**
     * @param ProductView[] $records
     * @return void
     * @throws ArchivingRecordErrorException
     */
    public function archiveProductViews(array $records): void {
        foreach ($records as $record) {
            if($this->repository->archiveProductViewRecord($record) == 0)
                throw new ArchivingRecordErrorException($record->getId());
        }
    }

    /**
     * @param User $user
     * @return void
     * @throws DeletingExpiredErrorException
     */
    public function deleteExpiredUser(User $user): void {

        if($this->repository->dropUser($user->getId()) == 0)
            throw new DeletingExpiredErrorException($user->getId());
    }
}