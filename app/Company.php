<?php

declare(strict_types=1);

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection as SupportCollection;

class Company extends Model
{
    private const ID_COLUMN         = 'id';
    private const NAME_COLUMN       = 'name';
    private const CREATED_AT_COLUMN = 'created_at';
    private const UPDATED_AT_COLUMN = 'updated_at';

    /** @var mixed|array|string[] */
    protected $guarded = [];

    /**
     * @return Collection|self[]
     */
    public static function getAll(): Collection
    {
        return self::all();
    }

    public static function getCompanyById(string $id): ?self
    {
        return self::find($id);
    }

    /**
     * @param array|string[] $ids
     *
     * @return Collection|self[]
     */
    public static function getCompaniesById(array $ids): Collection
    {
        return self::whereIn(self::ID_COLUMN, $ids)->get();
    }

    public function getId(): string
    {
        return (string) $this->attributes[self::ID_COLUMN];
    }

    public function setId(string $id): void
    {
        $this->attributes[self::ID_COLUMN] = $id;
    }

    public function getName(): string
    {
        return $this->attributes[self::NAME_COLUMN];
    }

    public function setName(string $name): void
    {
        $this->attributes[self::NAME_COLUMN] = $name;
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

    public function path(): string
    {
        return '/admin/companies/' . $this->getId();
    }

    public function users(): Relation
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users()->get();
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

    public function employees(): Relation
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees()->get();
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
     * @param SupportCollection|Position[] $positions
     */
    public function setPositions(SupportCollection $positions): void
    {
        $positionIds = $positions->map(static function (Position $position) {
            return $position->getID();
        })->toArray();
        $this->positions()->sync($positionIds);
    }

    public function registries(): Relation
    {
        return $this->belongsToMany(Registry::class);
    }

    /**
     * @return Collection|Registry[]
     */
    public function getRegistries(): Collection
    {
        return $this->registries()->get();
    }

    /**
     * @param array|string[] $ids
     */
    public function setRegistries(array $ids): void
    {
        $this->registries()->sync($ids);
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

    public function addTraining(Training $training): void
    {
        $this->trainings()->sync($training->getID(), false);
    }

    /**
     * @param Collection|Training[] $trainings
     */
    public function addTrainings(Collection $trainings): void
    {
        $trainingIds = $trainings->map(static function (Training $training) {
            return $training->getID();
        })->toArray();
        $this->trainings()->sync($trainingIds, false);
    }
}
