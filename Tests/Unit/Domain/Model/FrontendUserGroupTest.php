<?php

declare(strict_types=1);

namespace OliverKlee\FeUserExtraFields\Tests\Unit\Domain\Model;

use OliverKlee\FeUserExtraFields\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \OliverKlee\FeUserExtraFields\Domain\Model\FrontendUserGroup
 */
final class FrontendUserGroupTest extends UnitTestCase
{
    /**
     * @var FrontendUserGroup
     *
     * We can make this property private once we drop support for TYPO3 V9.
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new FrontendUserGroup();
    }

    /**
     * @test
     */
    public function isAbstractEntity(): void
    {
        self::assertInstanceOf(AbstractEntity::class, $this->subject);
    }

    /**
     * @test
     */
    public function getTitleInitiallyReturnsEmptyString(): void
    {
        $result = $this->subject->getTitle();

        self::assertSame('', $result);
    }

    /**
     * @test
     */
    public function getTitleInitiallyReturnsGivenTitleProvidedInConstructor(): void
    {
        $title = 'foo bar';
        $subject = new FrontendUserGroup($title);

        $result = $subject->getTitle();

        self::assertSame($title, $result);
    }

    /**
     * @test
     */
    public function setTitleSetsTitle(): void
    {
        $title = 'foo bar';

        $this->subject->setTitle($title);

        self::assertSame($title, $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getLockToDomainInitiallyReturnsEmptyString(): void
    {
        $result = $this->subject->getLockToDomain();

        self::assertSame('', $result);
    }

    /**
     * @test
     */
    public function setLockToDomainSetsLockToDomain(): void
    {
        $lockToDomain = 'example.com';

        $this->subject->setLockToDomain($lockToDomain);

        self::assertSame($lockToDomain, $this->subject->getLockToDomain());
    }

    /**
     * @test
     */
    public function getDescriptionInitiallyReturnsEmptyString(): void
    {
        $result = $this->subject->getDescription();

        self::assertSame('', $result);
    }

    /**
     * @test
     */
    public function setDescriptionSetsDescription(): void
    {
        $description = 'foo bar';

        $this->subject->setDescription($description);

        self::assertSame($description, $this->subject->getDescription());
    }
}
