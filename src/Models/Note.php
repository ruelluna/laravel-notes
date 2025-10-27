<?php

declare(strict_types=1);

namespace Arcanedev\LaravelNotes\Models;

use Arcanedev\Support\Database\PrefixedModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

/**
 * Class     Note
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int                                  $id
 * @property  string                               $content
 * @property  int                                  $noteable_id
 * @property  string                               $noteable_type
 * @property  int                                  $author_id
 * @property  \Carbon\Carbon                       $created_at
 * @property  \Carbon\Carbon                       $updated_at
 *
 * @property  \Illuminate\Database\Eloquent\Model  $author
 * @property  \Illuminate\Database\Eloquent\Model  $noteable
 */
class Note extends PrefixedModel
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'author_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'noteable_id',
        'noteable_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id'          => 'integer',
        'noteable_id' => 'integer',
        'author_id'   => 'integer',
    ];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Note constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $config = config('notes.database', []);

        $this->setConnection(Arr::get($config, 'connection'));
        $this->setPrefix(Arr::get($config, 'prefix'));
        $this->setTable(Arr::get($config, 'table', 'notes'));
    }

    /* -----------------------------------------------------------------
     |  Relationship
     | -----------------------------------------------------------------
     */

    /**
     * The noteable relationship.
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The author relationship.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(config('notes.authors.model'));
    }
}
