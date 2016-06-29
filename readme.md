## Eloquent Uuid

Simple laravel / eloquent behavior to use uuid as a primary key or as a separate field by listening to Eloquent's creating event.

### Usage

```
use EloquentUuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Uuid;
}
```

<strong> You can define the field to store the uuid in as follows (default: primary key) </strong>


```php
protected $uuidField = 'uuid';
```


<strong> You can define the uuid version as follows (default: version 1) </strong>


```php
protected $uuidVersion = 4;
```
currently this package only supports version 1 and 4


## Chosing Uuid version.

<strong>1- uuid1() generates a UUID based on the current time and the MAC address of the machine.</strong>

Pros: Useful if you want to be able to sort your UUIDs by creation time.
Cons: Potential privacy leakage since it reveals which computer it was generated on and at what time.
Collisions possible: If two UUIDs are generated at the exact same time (within 100 ns) on the same machine. (Or a few other unlikely marginal cases.)

<strong>2- uuid2() doesn't seem to be used anymore.</strong>

<strong>3- uuid3() generates a UUID by taking an MD5 hash of an arbitrary name that you choose within some namespace (e.g. URL, domain name, etc).</strong>

Pros: Provides a nice way of assigning blocks of UUIDs to different namespaces. Easy to reproduce the UUID from the name.
Cons: If you have a unique name already, why do you need a UUID?
Collisions possible: If you reuse a name within a namespace, or if there is a hash collision.

<strong>4- uuid4() generates a completely random UUID.</strong>

Pros: No privacy concerns. Don't have to generate unique names.
Cons: No structure to UUIDs.
Collisions possible: If you use a bad random number generator, reuse a random seed, or are very, very unlucky.

<strong>5- uuid5() is the same as uuid3(), except using a SHA-1 hash instead of MD5. Officially preferred over uuid3().</strong>
