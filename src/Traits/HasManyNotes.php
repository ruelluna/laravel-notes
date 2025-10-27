<?php

declare(strict_types=1);

namespace Arcanedev\LaravelNotes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class     HasManyNotes
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Database\Eloquent\Collection  notes
 */
trait HasManyNotes
{
    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * The notes relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes(): MorphMany
    {
        return $this->morphMany((string) config('notes.notes.model'), 'noteable');
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a note.
     */
    public function createNote(string $content, ?Model $author = null, bool $reload = true): \Arcanedev\LaravelNotes\Models\Note
    {
        /** @var \Arcanedev\LaravelNotes\Models\Note $note */
        $note = $this->notes()->create(
            $this->prepareNoteAttributes($content, $author)
        );

        if ($reload) {
            $relations = array_merge(
                ['notes'],
                method_exists($this, 'authoredNotes') ? ['authoredNotes'] : []
            );

            $this->load($relations);
        }

        return $note;
    }

    /**
     * Retrieve a note by its ID.
     */
    public function findNote(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->notes()->find($id);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Prepare note attributes.
     */
    protected function prepareNoteAttributes(string $content, ?Model $author = null): array
    {
        return [
            'author_id' => is_null($author) ? $this->getCurrentAuthorId() : $author->getKey(),
            'content'   => $content,
        ];
    }

    /**
     * Get the current author's id.
     */
    protected function getCurrentAuthorId(): ?int
    {
        return null;
    }
}
