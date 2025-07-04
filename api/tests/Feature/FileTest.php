<?php

use App\Enums\FileType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Models\File;

uses(RefreshDatabase::class);

it('stores an image file successfully and returns a preview URL', function () {
    Storage::fake('local');

    $file_type = FileType::PHOTO;
    $uploaded_file = UploadedFile::fake()->create('test.jpg', 500, 'image/jpeg');

    $response = $this->postJson('/api/files', [
        'type' => $file_type->value,
        'file' => $uploaded_file,
    ]);

    $response->assertStatus(201)
    ->assertJsonStructure([
        'data' => ['id', 'name', 'url', 'created_at']
    ]);

    $response->assertJson([
        'data' => [
            'id' => $response->json('data.id'),
            'name' => $uploaded_file->getClientOriginalName(),
            'url' => route('files.preview', ['file' => $response->json('data.id')]),
        ]
    ]);

    $saved_file = File::find($response->json('data.id'));

    $this->assertNotNull($saved_file, 'File record should exist in the database.');
    $this->assertNotNull($saved_file->path, 'File path should be stored in the database.');
    $this->assertEquals($file_type, $saved_file->type, 'File type should be stored in the database.');

    Storage::disk('local')->assertExists($saved_file->path);
});

it('returns files grouped by type on the index route', function () {
    $id_file = File::factory()->create([
        'name' => 'personal_ID.pdf',
        'type' => FileType::IDENTITY_DOCUMENT,
        'path' => 'files/' . Str::random(28) . '.pdf',
    ]);

    $photo_file_a = File::factory()->create([
        'name' => 'summer_vacation_a.jpg',
        'type' => FileType::PHOTO,
        'path' => 'files/' . Str::random(28) . '.jpg',
    ]);

    $photo_file_b = File::factory()->create([
        'name' => 'summer_vacation_b.png',
        'type' => FileType::PHOTO,
        'path' => 'files/' . Str::random(28) . '.png',
    ]);

    $visa_file = File::factory()->create([
        'name' => 'visa.png',
        'type' => FileType::VISA,
        'path' => 'files/' . Str::random(28) . '.png',
    ]);

    $this->getJson('/api/files')
        ->assertJson([
            'data' => [
                FileType::IDENTITY_DOCUMENT->value => [
                    [
                        'id' => $id_file->id,
                        'name' => 'personal_ID.pdf',
                        'url' => null,
                    ],
                ],
                FileType::PHOTO->value => [
                    [
                        'id' => $photo_file_a->id,
                        'name' => 'summer_vacation_a.jpg',
                        'url' => route('files.preview', ['file' => $photo_file_a->id]),
                    ],
                    [
                        'id' => $photo_file_b->id,
                        'name' => 'summer_vacation_b.png',
                        'url' => route('files.preview', ['file' => $photo_file_b->id]),
                    ],
                ],
                FileType::VISA->value => [
                    [
                        'id' => $visa_file->id,
                        'name' => 'visa.png',
                        'url' => route('files.preview', ['file' => $visa_file->id]),
                    ],
                ],
            ],
        ]);
});

it('returns the image content for a valid image preview URL', function () {
    Storage::fake('local');

    $file_content = base64_decode(
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
    );

    $path = 'files/' . Str::random(28) . '.png';

    $file = File::factory()->create([
        'name' => 'test_preview.jpg',
        'type' => FileType::PHOTO->value,
        'path' => $path,
    ]);

    Storage::disk('local')->put($path, $file_content);

    $response = $this->get(route('files.preview', ['file' => $file->id]));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'image/png');
    $response->assertStreamedContent($file_content);
});


it('returns a validation error for an unsupported file type', function () {
    Storage::fake('local');
    $unsupportedFile = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

    $response = $this->postJson('/api/files', [
        'type' => FileType::VISA->value,
        'file' => $unsupportedFile,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors('file');
});

it('deletes a file successfully', function () {
    Storage::fake('local');

    $image = base64_decode(
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
    );

    $file = File::factory()->create([
        'path' => 'files/' . Str::random(28) . '.png',
    ]);

    Storage::disk('local')->put($file->path, $image);

    $this->assertDatabaseHas('files', ['id' => $file->id]);
    Storage::disk('local')->assertExists($file->path);

    $response = $this->deleteJson('/api/files/' . $file->id);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('files', ['id' => $file->id]);
    Storage::disk('local')->assertMissing($file->path);
});

it('returns a null url for pdf files', function () {
    $filename = 'test.pdf';

    $pdf = File::factory()->create([
        'name' => $filename,
        'type' => FileType::IDENTITY_DOCUMENT,
        'path' => 'files/' . Str::random(28) . '.pdf',
    ]);

    $response = $this->getJson('/api/files')
        ->assertStatus(200);

    $response->assertJsonFragment([
        'id' => $pdf->id,
        'name' => $filename,
        'url' => null,
    ]);
});
