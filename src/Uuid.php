<?php

namespace EloquentUuid;

trait Uuid
{


    /**
     * Uuid constructor.
     */
    public function __construct()
    {

        // If the uuid is the primary key disable incrementing.
        if ($this->getUuidField() == $this->getKeyName()) {
            $this->incrementing = false;
        }

        parent::__construct();
    }


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName())
         */
        static::creating(function ($model) {
            $model->{$model->getUuidField()} = (string)$model->generateUuid();
        });
    }

    /**
     * Get a new version 1 (time based) UUID.
     * @return \Rhumsaa\Uuid\Uuid
     *
     * uuid1() generates a UUID based on the current time and the MAC address of the machine.
     *
     * Pros: Useful if you want to be able to sort your UUIDs by creation time.
     * Cons: Potential privacy leakage since it reveals which computer it was generated on and at what time.
     * Collisions possible: If two UUIDs are generated at the exact same time (within 100 ns) on the same machine. (Or a few other unlikely marginal cases.)
     *
     * uuid2() doesn't seem to be used anymore.
     *
     * uuid3() generates a UUID by taking an MD5 hash of an arbitrary name that you choose within some namespace (e.g. URL, domain name, etc).
     *
     * Pros: Provides a nice way of assigning blocks of UUIDs to different namespaces. Easy to reproduce the UUID from the name.
     * Cons: If you have a unique name already, why do you need a UUID?
     * Collisions possible: If you reuse a name within a namespace, or if there is a hash collision.
     *
     * uuid4() generates a completely random UUID.
     *
     * Pros: No privacy concerns. Don't have to generate unique names.
     * Cons: No structure to UUIDs.
     * Collisions possible: If you use a bad random number generator, reuse a random seed, or are very, very unlucky.
     */
    public function generateUuid()
    {
        $version = "uuid".$this->getUuidVersion();

        return \Rhumsaa\Uuid\Uuid::$version()->toString();
    }


    /**
     * @return mixed
     */
    public function getUuidField()
    {
        // if the uuidField is undefined return the primary key
        if (!$this->uuidField) {
            return $this->getKeyName();
        }
        return $this->uuidField;
    }

    /**
     *
     */
    public function getUuidVersion()
    {
        if ($this->uuidVersion) {
            if (!in_array($this->uuidVersion, [1, 4]))
                throw new \Exception("uuid ".$this->uuidVersion." is not supported or not valid.");

            return $this->uuidVersion;
        }

        return 1;
    }

}