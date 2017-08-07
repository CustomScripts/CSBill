<?php

declare(strict_types=1);

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ApiBundle\Tests\Serializer\Normalizer;

use CSBill\ApiBundle\Serializer\Normalizer\AdditionalContactDetailsNormalizer;
use CSBill\ClientBundle\Entity\AdditionalContactDetail;
use CSBill\ClientBundle\Entity\ContactType;
use CSBill\CoreBundle\Test\Traits\DoctrineTestTrait;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdditionalContactDetailsNormalizerTest extends TestCase
{
    use MockeryPHPUnitIntegration,
        DoctrineTestTrait;

    protected function setUp()
    {
        parent::setUp();
        $this->setupDoctrine();

        $type = new ContactType();
        $type->setName('email');
        $this->em->persist($type);
        $this->em->flush();
    }

    public function testSupportsNormalization()
    {
        $parentNormalizer = new class implements NormalizerInterface, DenormalizerInterface
        {
            public function normalize($object, $format = null, array $context = [])
            {
            }

            public function supportsNormalization($data, $format = null)
            {
            }

            public function supportsDenormalization($data, $type, $format = null)
            {
            }

            public function denormalize($data, $class, $format = null, array $context = [])
            {
            }
        };

        $normalizer = new AdditionalContactDetailsNormalizer($this->registry, $parentNormalizer);

        $this->assertTrue($normalizer->supportsNormalization(new AdditionalContactDetail()));
        $this->assertFalse($normalizer->supportsNormalization(AdditionalContactDetail::class));
    }

    public function testSupportsDenormalization()
    {
        $parentNormalizer = new class implements NormalizerInterface, DenormalizerInterface
        {
            public function normalize($object, $format = null, array $context = [])
            {
            }

            public function supportsNormalization($data, $format = null)
            {
            }

            public function supportsDenormalization($data, $type, $format = null)
            {
            }

            public function denormalize($data, $class, $format = null, array $context = [])
            {
            }
        };

        $normalizer = new AdditionalContactDetailsNormalizer($this->registry, $parentNormalizer);

        $this->assertTrue($normalizer->supportsDenormalization(null, AdditionalContactDetail::class));
        $this->assertFalse($normalizer->supportsDenormalization([], NormalizerInterface::class));
    }

    public function testNormalization()
    {
        $parentNormalizer = new class implements NormalizerInterface, DenormalizerInterface
        {
            public function normalize($object, $format = null, array $context = [])
            {
            }

            public function supportsNormalization($data, $format = null)
            {
            }

            public function supportsDenormalization($data, $type, $format = null)
            {
            }

            public function denormalize($data, $class, $format = null, array $context = [])
            {
            }
        };

        $normalizer = new AdditionalContactDetailsNormalizer($this->registry, $parentNormalizer);

        $additionalContactDetail = new AdditionalContactDetail();
        $type = new ContactType();
        $type->setName('email');
        $additionalContactDetail->setType($type)
            ->setValue('one@two.com');

        $this->assertSame(['type' => 'email', 'value' => 'one@two.com'], $normalizer->normalize($additionalContactDetail));
    }

    public function testDenormalization()
    {
        $parentNormalizer = new class implements NormalizerInterface, DenormalizerInterface
        {
            public function normalize($object, $format = null, array $context = [])
            {
            }

            public function supportsNormalization($data, $format = null)
            {
            }

            public function supportsDenormalization($data, $type, $format = null)
            {
            }

            public function denormalize($data, $class, $format = null, array $context = [])
            {
                $additionalContactDetail = new AdditionalContactDetail();
                $type = new ContactType();
                $type->setName($data['type']['name']);
                $additionalContactDetail->setType($type)
                    ->setValue($data['value']);

                return $additionalContactDetail;
            }
        };

        $entityRepository = $this->em->getRepository('CSBillClientBundle:ContactType');
        $this->registry->shouldReceive('getRepository')
            ->once()
            ->with('CSBillClientBundle:ContactType')
            ->andReturn($entityRepository);

        $normalizer = new AdditionalContactDetailsNormalizer($this->registry, $parentNormalizer);

        $additionalContactDetail = new AdditionalContactDetail();
        $additionalContactDetail->setType($entityRepository->find(1))
            ->setValue('one@two.com');

        $this->assertEquals($additionalContactDetail, $normalizer->denormalize(['type' => 'email', 'value' => 'one@two.com'], AdditionalContactDetail::class));
    }

    protected function getEntityNamespaces()
    {
        return [
            'CSBillClientBundle' => 'CSBill\ClientBundle\Entity',
        ];
    }

    protected function getEntities()
    {
        return [
            'CSBill\ClientBundle\Entity\ContactType',
        ];
    }
}
