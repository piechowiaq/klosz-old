<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Employee extends Model
{
//    use Searchable;

    private const ID_COLUMN         = 'id';
    private const NAME_COLUMN       = 'name';
    private const SURNAME_COLUMN    = 'surname';
    private const COMPANY_ID_COLUMN = 'company_id';
    private const NUMBER_COLUMN     = 'number';
    private const CREATED_AT_COLUMN = 'created_at';
    private const UPDATED_AT_COLUMN = 'updated_at';

    /** @var array|string[] */
    protected $guarded = [];

    public static function getEmployeeById(string $id): ?self
    {
        return self::find($id);
    }

    /**
     * @param array|string[] $ids
     *
     * @return Collection|self[]
     */
    public static function getEmployeesById(array $ids): Collection
    {
        return self::whereIn(self::ID_COLUMN, $ids)->get();
    }

    public function getID(): string
    {
        return (string) $this->attributes[self::ID_COLUMN];
    }

    public function setId(string $id): void
    {
        $this->attributes[self::ID_COLUMN] = $id;
    }

    public function getName(): string
    {
        return (string) $this->attributes[self::NAME_COLUMN];
    }

    public function setName(string $name): void
    {
        $this->attributes[self::NAME_COLUMN] = $name;
    }

    public function getSurname(): string
    {
        return (string) $this->attributes[self::SURNAME_COLUMN];
    }

    public function setSurname(string $surname): void
    {
        $this->attributes[self::SURNAME_COLUMN] = $surname;
    }

    public function company(): Relation
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return Collection|Company[]
     */
    public function getCompany(): Collection
    {
        return $this->company()->get();
    }

    public function setCompany(Company $company): void
    {
        $this->attributes[self::COMPANY_ID_COLUMN] = $company->getId();
    }

    public function getNumber(): int
    {
        return (int) $this->attributes[self::NUMBER_COLUMN];
    }

    public function setNumber(int $number): void
    {
        $this->attributes[self::NUMBER_COLUMN] = $number;
    }

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->attributes[self::CREATED_AT_COLUMN]);
    }

    public function setCreatedAtDateTime(DateTime $dateTime): void
    {
        $this->attributes[self::CREATED_AT_COLUMN] = $dateTime;
    }

    public function getUpdatedAt(): DateTime
    {
        return new DateTime($this->attributes[self::UPDATED_AT_COLUMN]);
    }

    public function setUpdatedAtDateTime(DateTime $dateTime): void
    {
        $this->attributes[self::UPDATED_AT_COLUMN] = $dateTime;
    }

    public function departments(): Relation
    {
        return $this->belongsToMany(Department::class);
    }

    /**
     * @return Collection|Department[]
     */
    public function getDepartments(): Collection
    {
        return $this->departments()->get();
    }

    /**
     * @param array|string[] $ids
     */
    public function setDepartments(array $ids): void
    {
        $this->departments()->sync($ids);
    }

    public function positions(): Relation
    {
        return $this->belongsToMany(Position::class);
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions()->get();
    }

    /**
     * @param array|string[] $ids
     */
    public function setPositions(array $ids): void
    {
        $this->positions()->sync($ids);
    }

    public function trainings(): Relation
    {
        return $this->belongsToMany(Training::class);
    }

    /**
     * @return Collection|Training[]
     */
    public function getTrainings(): Collection
    {
        return $this->trainings()->get();
    }

    /**
     * @param array|string[] $ids
     */
    public function setTrainings(array $ids): void
    {
        $this->trainings()->sync($ids);
    }

    public function certificates(): Relation
    {
        return $this->belongsToMany(Certificate::class);
    }

    /**
     * @return Collection|Certificate[]
     */
    public function getCertificates(): Collection
    {
        return $this->certificates()->get();
    }

    /**
     * @param array|string[] $ids
     */
    public function setCertificates(array $ids): void
    {
        $this->certificates()->sync($ids);
    }

    public function getFullNameAttribute(): string
    {
        return $this->getName() . ' ' . $this->getSurname();
    }

    public function path(): string
    {
        return '/admin/employees/' . $this->getID();
    }

    public function userPath(Company $company): string
    {
        return '/' . $company->getId() . '/employees/' . $this->getID();
    }

    public function getTrainingsCountAttribute(): int
    {
        return $this->trainings()->count();
    }

    public function scopeCertified($query, Training $training, Company $company)
    {
        return $query->where('company_id', $company->getId())->whereHas('certificates', static function ($q) use ($training): void {
            $q->where('expiry_date', '>', Carbon::now())
                ->where('training_id', $training->getID());
        })->get();
    }

//    public function toSearchableArray()
//    {
//        return $this->toArray() + ['path' => $this->userpath($this['company_id'])];
//    }
//
//    public static function search($query)
//    {
//        return empty($query) ? static::query()
//            : static::where('name', 'like', '%'.$query.'%')
//                ->orWhere('surname', 'like', '%'.$query.'%');
//    }
}
