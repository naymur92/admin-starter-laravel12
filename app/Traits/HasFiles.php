<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasFiles
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasFiles
{
    /**
     * Get all files associated with the model.
     *
     * @return Collection
     */
    public function files(): Collection
    {
        $this->ensureIsModel();
        return File::query()
            ->where('operation_name', $this->getTable())
            ->where('table_id', $this->getKey())
            ->get();
    }

    /**
     * Delete a single file (physically + DB) and mark deleted_by.
     *
     * @param File $file
     * @param int|null $deletedBy
     * @return bool|null
     */
    public function removeFile(File $file, ?int $deletedBy = null): ?bool
    {
        if (file_exists(public_path($file->path . '/' . $file->name))) {
            unlink(public_path($file->path . '/' . $file->name));
        }

        $file->deleted_by = $deletedBy ?? Auth::id();
        return $file->delete();
    }

    /**
     * Upload one or multiple files and associate with this model.
     *
     * @param UploadedFile|array $files
     * @param array $allowedExtensions
     * @param int $maxSizeBytes
     * @param string|null $info
     * @param bool $deleteExisting
     * @param string|null $customPath Custom file path (overrides default)
     * @return Collection
     */
    public function saveFiles(
        $files,
        array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'],
        int $maxSizeBytes = 1048576, // 1 MB
        ?string $info = null,
        bool $deleteExisting = false,
        ?string $customPath = null
    ): Collection {
        $files = is_array($files) ? $files : [$files];
        $savedFiles = collect();

        // Delete existing files if requested (only files matching the info type)
        if ($deleteExisting && $info && $this->files()->isNotEmpty()) {
            foreach ($this->files()->where('info', $info) as $existing) {
                $this->removeFile($existing);
            }
        }

        $this->ensureIsModel();
        $opName = $this->getTable();
        $tableId = $this->getKey();
        $filePath = $customPath ?? ('assets/uploads/' . $opName);

        foreach ($files as $index => $file) {
            if (!in_array(strtolower($file->extension()), $allowedExtensions)) {
                continue;
            }

            // Check size before attempting any operations on the file
            try {
                $fileSize = $file->getSize();
            } catch (\Exception $e) {
                continue; // Skip file if size cannot be determined
            }

            if ($fileSize > $maxSizeBytes) {
                continue;
            }

            // Append index for multiple files
            $suffix = count($files) > 1 ? "($index)" : '';
            $fileName = $tableId . '_' . time() . $suffix . '.' . $file->extension();

            // Move file
            $file->move(public_path($filePath), $fileName);

            // Save in DB
            $savedFiles->push(
                File::create([
                    'operation_name' => $opName,
                    'table_id'       => $tableId,
                    'path'           => $filePath,
                    'name'           => $fileName,
                    'size'           => $fileSize,
                    'info'           => $info,
                    'created_by'     => Auth::id(),
                ])
            );
        }

        return $savedFiles;
    }

    /**
     * Delete all files associated with this model.
     *
     * @return int
     */
    public function deleteAllFiles(): int
    {
        $files = $this->files();

        foreach ($files as $file) {
            $file->remove();
        }

        return $files->count();
    }

    /**
     * Ensure the using class is an Eloquent model.
     *
     * @return void
     */
    private function ensureIsModel(): void
    {
        if (! $this instanceof Model) {
            throw new \LogicException('The HasFiles trait must be used within an Eloquent Model instance.');
        }
    }
}
