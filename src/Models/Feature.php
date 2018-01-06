<?php

namespace Laravel\PricingPlans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Laravel\PricingPlans\Models\Concerns\Resettable;

/**
 * Class Feature
 * @package Laravel\PricingPlans\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $sort_order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Feature extends Model
{
    use Resettable;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'interval_unit',
        'interval_count',
        'sort_order',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Plan constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(Config::get('plans.tables.features'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plans()
    {
        return $this->belongsToMany(
            Config::get('plans.models.Plan'),
            Config::get('plans.tables.plans'),
            'feature_id',
            'plan_id'
        )->using(Config::get('plans.models.PlanFeature'));
    }

    /**
     * Get feature usage.
     *
     * This will return all related subscriptions usages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage()
    {
        return $this->hasMany(
            Config::get('plans.models.PlanSubscriptionUsage'),
            Config::get('plans.tables.plan_subscription_usages'),
            'feature_id',
            'id'
        );
    }
}
