<?php

namespace EloquentUuid;

trait Uuid
{
    /**
     * The "booting" method of the model.
     */
    protected static function bootUuid()
    {
        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName()).
         */
        static::creating(function ($model) {
            $model->{$model->getUuidField()} = (string) $model->generateUuid();
        });
    }

    /**
     * @return mixed
     */
    public function getUuidField()
    {
        // if the uuidField is undefined return the primary key
        if (!$this->uuidField) {
            $field = $this->getKeyName();
        } else {
            $field = $this->uuidField;
        }

        // If the uuid is the primary key disable incrementing.
        if ($field == $this->getKeyName()) {
            $this->incrementing = false;
        }

        return $field;
    }

    /**
     * @return int
     *
     * @throws \Exception
     */
    public function getUuidVersion()
    {
        if ($this->uuidVersion) {
            if (!in_array($this->uuidVersion, [1, 4])) {
                throw new \Exception('uuid '.$this->uuidVersion.' is not supported or not valid.');
            }

            return $this->uuidVersion;
        }

        return 1;
    }

    /**
     * Get a new Uuid.
     *
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
        $version = 'uuid'.$this->getUuidVersion();

        return \Ramsey\Uuid\Uuid::$version()->toString();
    }

    /**
     * Scope a query to only include models matching the supplied ID or UUID.
     * Returns the model by default, or supply a second flag `false` to get the Query Builder instance.
     *     *
     * @param string $uuid The UUID of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model | Null
     */
    public function findByUuid($uuid)
    {
        return $this->where('uuid', $uuid)->first();
    }

    /**
     * Scope a query to only include models matching the supplied ID or UUID.
     * Returns the model by default, or supply a second flag `false` to get the Query Builder instance.
     *
     * @param \Illuminate\Database\Schema\Builder $query The Query Builder instance.
     * @param string                              $uuid  The UUID of the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIdOrUuId($query, $id_or_uuid)
    {

        $query = $query->where(function ($query) use ($id_or_uuid) {
            $query->where('id', $id_or_uuid)
                ->orWhere('uuid', $id_or_uuid);
        });

        return $query;
    }

    /**
     * Scope a query to only include models matching the supplied UUID.
     * Returns the model by default, or supply a second flag `false` to get the Query Builder instance.
     *
     * @param  \Illuminate\Database\Schema\Builder $query The Query Builder instance.
     * @param  string                              $uuid  The UUID of the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUuid($query, $uuid, $first = true)
    {
        $query = $query->where('uuid', $uuid);

        return $query;
    }
}
