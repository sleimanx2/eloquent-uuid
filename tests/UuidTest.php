<?php

namespace EloquentUuid\Tests;


use EloquentUuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class UuidTest extends \PHPUnit_Framework_TestCase
{

    public $modelData = array('name' => 'Test Name');

    /**
     * Testing Model
     * @return TestModel
     * @internal param string $type
     */
    public function getModel()
    {
        $model = new TestModel();
        $model->fill($this->modelData);
        return $model;
    }


    /**
     * @test
     */
    public function it_sets_the_uuid_as_a_primary_by_default(){

        $model = $this->getModel();
        $this->assertTrue($model->getKeyName() == $model->getUuidField());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_set_a_custom_uuid_field(){
        $model = $this->getModel();
        $model->uuidField = 'uuid';
        $this->assertTrue($model->getUuidField() == 'uuid');
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_uses_uuid1_as_default_uuid_generator(){
        $model = $this->getModel();
        $this->assertTrue($model->getUuidVersion() == 1);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_set_a_custom_uuid_generator(){
        $model = $this->getModel();
        $model->uuidVersion = 4;
        $this->assertTrue($model->getUuidVersion() == 4);
    }


    /**
     * @test
     */
    public function it_fills_the_uuid_attribute_correctly(){

        $model = $this->getModel();

        $model->{$model->getUuidField()} = (string)$model->generateUuid();

        $attributes = $model->toArray();

        $this->assertTrue(isset($attributes[$model->getUuidField()]));

        // custom field
        $model = $this->getModel();

        $model->uuidField = 'uuid';

        $model->{$model->getUuidField()} = (string)$model->generateUuid();

        $attributes = $model->toArray();

        $this->assertTrue(isset($attributes['uuid']));


    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function it_throw_an_exception_for_invalid_uuid_version(){

        $model = $this->getModel();
        $model->uuidVersion = 15;
        $model->getUuidVersion();
    }



}


class TestModel extends Model {

    use Uuid;

    protected $fillable = array('name');
}

