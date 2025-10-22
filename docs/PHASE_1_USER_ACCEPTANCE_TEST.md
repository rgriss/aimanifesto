# Phase 1 User Acceptance Test Guide

**Purpose:** Verify that the Content Export/Import system works correctly in real-world scenarios.

**Duration:** ~15 minutes

**Prerequisites:**
- Local development environment running
- Database with some categories and tools (or run `php artisan db:seed`)

---

## Test 1: Export Command Basic Functionality

### Steps:

1. **Check current database state:**
```bash
php artisan tinker --execute="echo 'Categories: ' . App\Models\Category::count() . PHP_EOL; echo 'Tools: ' . App\Models\Tool::count();"
```
✅ **Expected:** Shows count of categories and tools in your database.

2. **Run export command:**
```bash
php artisan content:export
```

✅ **Expected output:**
```
Exporting content...

Exported categories       12
Exported tools           87
Saved to                 F:\code\aimanifesto\database\content\snapshots\snapshot-2025-10-22.json
Updated                  F:\code\aimanifesto\database\content\latest.json

Export completed in 1.23 seconds
```

3. **Verify snapshot file exists:**
```bash
ls -la database/content/snapshots/
```
✅ **Expected:** See a file named `snapshot-YYYY-MM-DD.json`

4. **Verify latest.json exists:**
```bash
ls -la database/content/latest.json
```
✅ **Expected:** File exists and was just created/updated

---

## Test 2: Examine Export File Structure

### Steps:

1. **Open the snapshot file:**
```bash
# Windows
notepad database/content/latest.json

# Mac/Linux
cat database/content/latest.json | head -50
```

2. **Verify JSON structure:**

✅ **Check for these sections:**
```json
{
  "metadata": {
    "version": "1.0",
    "exported_at": "...",
    "schema_version": "1.0",
    "record_counts": { ... }
  },
  "categories": [ ... ],
  "tools": [ ... ]
}
```

3. **Verify category data:**
```bash
# Count categories in JSON (should match database)
php -r "\$data = json_decode(file_get_contents('database/content/latest.json'), true); echo count(\$data['categories']);"
```
✅ **Expected:** Number matches your database count

4. **Verify tool data includes category slugs:**
```bash
# Check first tool's category relationship
php -r "\$data = json_decode(file_get_contents('database/content/latest.json'), true); echo \$data['tools'][0]['category_slug'];"
```
✅ **Expected:** Shows a category slug (not a numeric ID)

---

## Test 3: Import Command - Fresh Database

### Steps:

1. **Backup current database state:**
```bash
php artisan content:export --output=database/content/backup-before-test.json
```

2. **Record current counts:**
```bash
php artisan tinker --execute="echo 'Categories: ' . App\Models\Category::count() . PHP_EOL; echo 'Tools: ' . App\Models\Tool::count();"
```

3. **Delete all data (⚠️ destructive - local only!):**
```bash
php artisan tinker --execute="App\Models\Tool::query()->delete(); App\Models\Category::query()->delete(); echo 'Deleted all data';"
```

4. **Verify database is empty:**
```bash
php artisan tinker --execute="echo 'Categories: ' . App\Models\Category::count() . PHP_EOL; echo 'Tools: ' . App\Models\Tool::count();"
```
✅ **Expected:** Both show `0`

5. **Import from latest snapshot:**
```bash
php artisan content:import
```

✅ **Expected output:**
```
Importing content from database/content/latest.json...

Validating snapshot...
Schema version               1.0 (compatible)
Exported at                  2025-10-22 14:30:00

Processing categories...
Created                      12
Updated                      0
Skipped                      0

Processing tools...
Created                      87
Updated                      0
Skipped                      0

Import completed in 3.45 seconds
```

6. **Verify data was restored:**
```bash
php artisan tinker --execute="echo 'Categories: ' . App\Models\Category::count() . PHP_EOL; echo 'Tools: ' . App\Models\Tool::count();"
```
✅ **Expected:** Counts match original numbers

7. **Verify relationships work:**
```bash
php artisan tinker --execute="echo App\Models\Tool::with('category')->first()->name . ' → ' . App\Models\Tool::first()->category->name;"
```
✅ **Expected:** Shows a tool name and its category name (relationship intact)

---

## Test 4: Import Command - Upsert Behavior

### Steps:

1. **Run import again (on top of existing data):**
```bash
php artisan content:import
```

✅ **Expected output:**
```
Processing categories...
Created                      0
Updated                      12    ← Should update, not duplicate!
Skipped                      0

Processing tools...
Created                      0
Updated                      87    ← Should update, not duplicate!
Skipped                      0
```

2. **Verify no duplicates were created:**
```bash
php artisan tinker --execute="echo 'Categories: ' . App\Models\Category::count() . PHP_EOL; echo 'Tools: ' . App\Models\Tool::count();"
```
✅ **Expected:** Same counts as before (no duplicates)

3. **Test updating existing data:**
```bash
# Modify a category name in database
php artisan tinker --execute="App\Models\Category::first()->update(['name' => 'MODIFIED NAME']); echo 'Modified first category';"

# Import should revert it
php artisan content:import

# Check if name was reverted
php artisan tinker --execute="echo App\Models\Category::first()->name;"
```
✅ **Expected:** Name reverted to original value from snapshot

4. **Test that views_count is preserved:**
```bash
# Set high views count on a tool
php artisan tinker --execute="App\Models\Tool::first()->update(['views_count' => 9999]); echo 'Set views to 9999';"

# Import should preserve it
php artisan content:import

# Check views count
php artisan tinker --execute="echo 'Views: ' . App\Models\Tool::first()->views_count;"
```
✅ **Expected:** Shows `9999` (views_count preserved during import)

---

## Test 5: Custom Export Path

### Steps:

1. **Export to custom location:**
```bash
php artisan content:export --output=storage/app/custom-export.json
```

2. **Verify file created:**
```bash
ls -la storage/app/custom-export.json
```
✅ **Expected:** File exists at custom location

3. **Import from custom file:**
```bash
php artisan content:import storage/app/custom-export.json
```
✅ **Expected:** Import succeeds

4. **Clean up:**
```bash
rm storage/app/custom-export.json
```

---

## Test 6: Specific Snapshot Import

### Steps:

1. **Create a tagged snapshot:**
```bash
php artisan content:export --output=database/content/snapshots/test-v1.0.0.json
```

2. **Modify database:**
```bash
php artisan tinker --execute="App\Models\Category::first()->update(['name' => 'CHANGED']);"
```

3. **Import specific snapshot:**
```bash
php artisan content:import database/content/snapshots/test-v1.0.0.json
```

4. **Verify restoration:**
```bash
php artisan tinker --execute="echo App\Models\Category::first()->name;"
```
✅ **Expected:** Original name restored

5. **Clean up:**
```bash
rm database/content/snapshots/test-v1.0.0.json
```

---

## Test 7: Error Handling

### Steps:

1. **Test with missing file:**
```bash
php artisan content:import database/content/nonexistent.json
```
✅ **Expected:** Error message about file not found, command fails gracefully

2. **Test with invalid JSON:**
```bash
echo "invalid json {{{" > database/content/test-invalid.json
php artisan content:import database/content/test-invalid.json
```
✅ **Expected:** Error message about invalid JSON

3. **Clean up:**
```bash
rm database/content/test-invalid.json
```

---

## Test 8: Production Workflow Simulation

### Steps:

1. **Simulate production export:**
```bash
# Export current state
php artisan content:export --output=database/content/snapshots/production-2025-10-22.json
```

2. **Simulate local import:**
```bash
# Import the "production" snapshot
php artisan content:import database/content/snapshots/production-2025-10-22.json
```

3. **Verify success:**
```bash
php artisan tinker --execute="echo 'Categories: ' . App\Models\Category::count() . PHP_EOL; echo 'Tools: ' . App\Models\Tool::count();"
```
✅ **Expected:** All data present and correct

4. **Clean up:**
```bash
rm database/content/snapshots/production-2025-10-22.json
```

---

## Test 9: JSON Field Integrity

### Steps:

1. **Check that JSON arrays are preserved:**
```bash
php artisan tinker --execute="
\$tool = App\Models\Tool::whereNotNull('features')->first();
if (\$tool) {
    echo 'Tool: ' . \$tool->name . PHP_EOL;
    echo 'Features: ' . json_encode(\$tool->features) . PHP_EOL;
    echo 'Use Cases: ' . json_encode(\$tool->use_cases) . PHP_EOL;
}
"
```
✅ **Expected:** Shows tool with properly formatted JSON arrays

2. **Export and import:**
```bash
php artisan content:export
php artisan content:import
```

3. **Verify arrays still correct:**
```bash
php artisan tinker --execute="
\$tool = App\Models\Tool::whereNotNull('features')->first();
if (\$tool) {
    echo 'Features count: ' . count(\$tool->features ?? []) . PHP_EOL;
}
"
```
✅ **Expected:** Same number of features as before

---

## Test 10: Full Round-Trip with Real Data

### Final Integration Test

1. **Record current state:**
```bash
# Get checksums of all data
php artisan tinker --execute="
echo 'Category checksum: ' . md5(json_encode(App\Models\Category::orderBy('id')->get()->toArray())) . PHP_EOL;
echo 'Tool checksum: ' . md5(json_encode(App\Models\Tool::orderBy('id')->get()->toArray())) . PHP_EOL;
"
```

2. **Export:**
```bash
php artisan content:export --output=database/content/snapshots/round-trip-test.json
```

3. **Delete all data:**
```bash
php artisan tinker --execute="App\Models\Tool::query()->delete(); App\Models\Category::query()->delete();"
```

4. **Import:**
```bash
php artisan content:import database/content/snapshots/round-trip-test.json
```

5. **Verify data integrity:**
```bash
# Get checksums again (should match original)
php artisan tinker --execute="
echo 'Category checksum: ' . md5(json_encode(App\Models\Category::orderBy('id')->get()->toArray())) . PHP_EOL;
echo 'Tool checksum: ' . md5(json_encode(App\Models\Tool::orderBy('id')->get()->toArray())) . PHP_EOL;
"
```
✅ **Expected:** Checksums DIFFER (because views_count is preserved, not exported values)

But verify data manually:
```bash
php artisan tinker --execute="
\$original = json_decode(file_get_contents('database/content/snapshots/round-trip-test.json'), true);
\$current = App\Models\Category::count();
echo 'Expected categories: ' . count(\$original['categories']) . PHP_EOL;
echo 'Actual categories: ' . \$current . PHP_EOL;
"
```
✅ **Expected:** Numbers match

6. **Clean up:**
```bash
rm database/content/snapshots/round-trip-test.json
```

---

## ✅ Success Criteria

All tests should pass with:
- ✅ Export creates valid JSON files
- ✅ Import restores data completely
- ✅ Upsert prevents duplicates
- ✅ Relationships (category_id) maintained correctly
- ✅ JSON arrays preserved
- ✅ Views count preserved on update
- ✅ Error handling works gracefully
- ✅ Custom paths supported
- ✅ Specific snapshot imports work

---

## 🐛 If Something Fails

1. **Check Laravel logs:**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

2. **Run automated tests:**
   ```bash
   php artisan test --filter=Content
   ```

3. **Verify database connection:**
   ```bash
   php artisan tinker --execute="DB::select('select 1');"
   ```

4. **Check file permissions:**
   ```bash
   ls -la database/content/
   ```

---

## Next Steps After Testing

Once all tests pass:

1. **Create your first production snapshot:**
   ```bash
   php artisan content:export --output=database/content/snapshots/production-baseline.json
   git add database/content/snapshots/production-baseline.json
   git commit -m "Production baseline snapshot"
   ```

2. **Deploy to production** and verify:
   ```bash
   # SSH to production server
   php artisan content:export
   # Verify export works
   ```

3. **Test production → local sync:**
   ```bash
   # Download from production
   scp user@server:/path/to/latest.json database/content/

   # Import locally
   php artisan content:import
   ```

---

## 📋 Test Results

| Test | Status | Notes |
|------|--------|-------|
| Export Basic | ⬜ | |
| Export Structure | ⬜ | |
| Import Fresh DB | ⬜ | |
| Import Upsert | ⬜ | |
| Custom Path | ⬜ | |
| Specific Snapshot | ⬜ | |
| Error Handling | ⬜ | |
| Production Workflow | ⬜ | |
| JSON Integrity | ⬜ | |
| Round Trip | ⬜ | |

**Tester:** _______________
**Date:** _______________
**Overall Result:** ⬜ Pass / ⬜ Fail
