<?php

declare(strict_types=1);

namespace OliverKlee\FeUserExtraFields\Tests\Functional\Domain\Repository;

use OliverKlee\FeUserExtraFields\Domain\Model\FrontendUser;
use OliverKlee\FeUserExtraFields\Domain\Model\FrontendUserGroup;
use OliverKlee\FeUserExtraFields\Domain\Model\FrontendUserWithCountry;
use OliverKlee\FeUserExtraFields\Domain\Repository\FrontendUserWithCountryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\FeUserExtraFields\Domain\Repository\FrontendUserWithCountryRepository
 */
final class FrontendUserWithCountryRepositoryTest extends FunctionalTestCase
{
    /**
     * @var array<int, string>
     */
    protected $coreExtensionsToLoad = ['extensionmanager'];

    /**
     * @var array<int, non-empty-string>
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/feuserextrafields', 'typo3conf/ext/static_info_tables'];

    /**
     * @var FrontendUserWithCountryRepository
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $objectManager->get(FrontendUserWithCountryRepository::class);
    }

    /**
     * @test
     */
    public function findAllForNoRecordsReturnsEmptyContainer(): void
    {
        $container = $this->subject->findAll();

        self::assertCount(0, $container);
    }

    /**
     * @test
     */
    public function findByUidForExistingRecordReturnsModelWithAllScalarData(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/UserWithAllScalarData.xml');

        $model = $this->subject->findByUid(1);

        self::assertInstanceOf(FrontendUserWithCountry::class, $model);
        self::assertSame('max', $model->getUsername());
        self::assertSame('luif3ui4t12', $model->getPassword());
        self::assertSame('Max M. Minimau', $model->getName());
        self::assertSame('Max', $model->getFirstName());
        self::assertSame('Murri', $model->getMiddleName());
        self::assertSame('Minimau', $model->getLastName());
        self::assertSame('Near the heating 4', $model->getAddress());
        self::assertSame('+49 1111 1233456-78', $model->getTelephone());
        self::assertSame('+49 1111 1233456-79', $model->getFax());
        self::assertSame('max@example.com', $model->getEmail());
        self::assertSame('example.com', $model->getLockToDomain());
        self::assertSame('Head of fur', $model->getTitle());
        self::assertSame('01234', $model->getZip());
        self::assertSame('Kattingen', $model->getCity());
        self::assertSame('United States of CAT', $model->getCountry());
        self::assertSame('www.example.com', $model->getWww());
        self::assertSame('Cat Scans Inc.', $model->getCompany());
        self::assertEquals(new \DateTime('2022-04-02T18:00'), $model->getLastLogin());
        self::assertTrue($model->getPrivacy());
        self::assertSame('NRW', $model->getZone());
        self::assertSame('NLD', $model->getStaticInfoCountry());
        self::assertSame('Welcome, Max MM!', $model->getFullSalutation());
        self::assertSame(FrontendUser::GENDER_FEMALE, $model->getGender());
        self::assertEquals(new \DateTime('2022-04-02T00:00'), $model->getDateOfBirth());
        self::assertSame(FrontendUser::STATUS_JOB_SEEKING_FULL_TIME, $model->getStatus());
        self::assertSame('Here we go!', $model->getComments());
    }

    /**
     * @test
     */
    public function initializesUserGroupsWithEmptyStorage(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/UserWithAllScalarData.xml');

        $model = $this->subject->findByUid(1);
        self::assertInstanceOf(FrontendUser::class, $model);

        $groups = $model->getUserGroup();
        self::assertInstanceOf(ObjectStorage::class, $groups);
        self::assertCount(0, $groups);
    }

    /**
     * @test
     */
    public function mapsUserGroupsAssociation(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/UserWithTwoGroups.xml');

        $model = $this->subject->findByUid(1);
        self::assertInstanceOf(FrontendUser::class, $model);

        $groups = $model->getUserGroup();
        self::assertInstanceOf(ObjectStorage::class, $groups);
        self::assertCount(2, $groups);
        $groupsAsArray = $groups->toArray();
        self::assertInstanceOf(FrontendUserGroup::class, $groupsAsArray[0]);
        self::assertInstanceOf(FrontendUserGroup::class, $groupsAsArray[1]);
    }

    /**
     * @test
     */
    public function initializesImageWithEmptyStorage(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/UserWithAllScalarData.xml');

        $model = $this->subject->findByUid(1);
        self::assertInstanceOf(FrontendUser::class, $model);

        $image = $model->getImage();
        self::assertInstanceOf(ObjectStorage::class, $image);
        self::assertCount(0, $image);
    }

    /**
     * @test
     */
    public function mapsImageAssociation(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/UserWithImage.xml');

        $model = $this->subject->findByUid(1);
        self::assertInstanceOf(FrontendUser::class, $model);

        $images = $model->getImage();
        self::assertInstanceOf(ObjectStorage::class, $images);
        self::assertCount(1, $images);
        $imagesAsArray = $images->toArray();
        $firstImage = $imagesAsArray[0];
        self::assertInstanceOf(FileReference::class, $firstImage);
        self::assertSame(1, $firstImage->getUid());
    }
}
