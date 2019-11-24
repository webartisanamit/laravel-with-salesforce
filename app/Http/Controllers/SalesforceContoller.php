<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Forrest;

class SalesforceContoller extends Controller
{
    public function connect(Request $request)
    {
        $auth = Forrest::authenticate();
        echo $auth->getContent();
    }


    public function callback(Request $request)
    {
        Forrest::callback();
        return redirect('/salesforce/products');
    }

    public function getProducts($id = null)
    {
        $select = 'Id,Name,ProductCode,Description,IsActive,CreatedDate,CreatedById,ExternalId,QuantityUnitOfMeasure,StockKeepingUnit';

        if ($id) {
            $products = Forrest::sobjects("Product2/$id");
        } else {
            $products = Forrest::query("SELECT $select FROM Product2");
        }

        return $products;
    }

    public function createProduct()
    {
        $rand = rand(100, 999);

        $data = [
            'Name'                  => "Test Product $rand",
            'ProductCode'           => "TP0$rand",
            'Description'           => "Test Product $rand product is only for testing",
            'QuantityUnitOfMeasure' => rand(5, 10),
            'StockKeepingUnit'      => rand(10, 50),
            'IsActive'              => 1,
        ];

        $product = Forrest::sobjects('Product2', [
            'method' => 'post',
            'body'   => $data,
        ]);

        $productId = $product['id'] ?? '';
        return redirect("/salesforce/products/$productId");
    }

    public function getOrders()
    {
        $select = 'Id';
        $orders = Forrest::query("SELECT $select FROM Order");

        return $orders;
    }

    public function getPets($id = null)
    {
        $select = 'Id,OwnerId,CreatedById,Name,id__c,type__c,gender__c,breed_label__c,age__c,about__c,CreatedDate';

        if ($id) {
            $pets = Forrest::sobjects("Pet__c/$id");
        } else {
            $pets = Forrest::query("SELECT $select FROM Pet__c");
        }

        return $pets;
    }

    public function createPet()
    {
        $petId          = '';
        $rand           = rand(100, 999);
        $typeArray      = ['Dog', 'Cat'];
        $type           = $typeArray[array_rand($typeArray)];
        $genderArray    = ['Male', 'Female'];
        $breeds         = ['Dog' => ['Pit Bull Terrier', 'American Bulldog', 'Australian Shepherd'], 'Cat' => ['Domestic Short Hair', 'Tabby', 'Siamese']];
        $typeBreeds     = $breeds[$type];

        $data = [
            'Name'              => "Pet $rand",
            'about__c'          => "This Pet $rand is very good pet",
            'age__c'            => rand(5, 10),
            'breed_label__c'    => $typeBreeds[array_rand($typeBreeds)],
            'gender__c'         => $genderArray[array_rand($genderArray)],
            'type__c'           => $type,
        ];

        try {
            $pet = Forrest::sobjects('Pet__c', [
                'method' => 'post',
                'body'   => $data,
            ]);

            $petId = $pet['id'] ?? '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect("/salesforce/pets/$petId");
    }
}
