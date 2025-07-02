<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\CouponDiscountEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $iphone = new Product();
        $iphone->setName('Iphone');
        $iphone->setPrice(10000);
        $manager->persist($iphone);

        $headphones = new Product();
        $headphones->setName('Headphones');
        $headphones->setPrice(2000);
        $manager->persist($headphones);

        $case = new Product();
        $case->setName('Cover');
        $case->setPrice(1000);
        $manager->persist($case);

        $couponFixed = new Coupon();
        $couponFixed->setCode('D15');
        $couponFixed->setDiscountType(CouponDiscountEnum::FIXED);// todo: enum
        $couponFixed->setValue(1500);
        $manager->persist($couponFixed);

        $coupon10 = new Coupon();
        $coupon10->setCode('P10');
        $coupon10->setDiscountType(CouponDiscountEnum::PERCENT);
        $coupon10->setValue(1000);
        $manager->persist($coupon10);

        $coupon100 = new Coupon();
        $coupon100->setCode('P100');
        $coupon100->setDiscountType(CouponDiscountEnum::PERCENT);
        $coupon100->setValue(10000);
        $manager->persist($coupon100);

        $manager->flush();
    }
}
