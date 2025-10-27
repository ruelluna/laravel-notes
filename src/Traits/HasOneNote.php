<?php

declare(strict_types=1);

namespace Arcanedev\LaravelNotes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait     HasOneNote
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Arcanedev\LaravelNotes\Models\Note  note
 */
trait HasOneNote
{
    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Relation to ONE note.
     */
    public function note(): MorphOne
    {
        return $this->morphOne(config('notes.notes.model'), 'noteable');
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
        if ($this->note)
            $this->note->delete();

        /** @var \Arcanedev\LaravelNotes\Models\Note $note */
        $note = $this->note()->create(
            $this->prepareNoteAttributes($content, $author)
        );

        if ($reload)
            $this->load(['note']);

        return $note;
    }

    /**
     * Update a note.
     */
    public function updateNote(string $content, ?Model $author = null, bool $reload = true): bool
    {
        $updated = $this->note->update(
            $this->prepareNoteAttributes($content, $author)
        );

        if ($reload) $this->load(['note']);

        return $updated;
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
