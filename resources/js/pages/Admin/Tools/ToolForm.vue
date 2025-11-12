<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { Link } from '@inertiajs/vue3';
import { X, Plus } from 'lucide-vue-next';
import { ref } from 'vue';

interface Category {
    id: number;
    name: string;
}

interface FormData {
    category_id: number | string;
    name: string;
    slug: string;
    description: string;
    long_description: string;
    website_url: string;
    documentation_url: string;
    logo_url: string;
    screenshot_url: string;
    pricing_model: string;
    price_description: string;
    ryan_rating: number | null;
    ryan_notes: string;
    ryan_last_used: string;
    features: string[];
    use_cases: string[];
    integrations: string[];
    is_featured: boolean;
    is_active: boolean;
    first_reviewed_at: string;
    upvotes: number;
    downvotes: number;
}

interface Props {
    form: any; // Inertia form object
    categories: Category[];
    submitLabel: string;
}

const props = defineProps<Props>();
const emit = defineEmits(['submit']);

// Local state for array inputs
const newFeature = ref('');
const newUseCase = ref('');
const newIntegration = ref('');
const screenshotPreview = ref<string | null>(null);

const addFeature = () => {
    if (newFeature.value.trim()) {
        props.form.features.push(newFeature.value.trim());
        newFeature.value = '';
    }
};

const removeFeature = (index: number) => {
    props.form.features.splice(index, 1);
};

const addUseCase = () => {
    if (newUseCase.value.trim()) {
        props.form.use_cases.push(newUseCase.value.trim());
        newUseCase.value = '';
    }
};

const removeUseCase = (index: number) => {
    props.form.use_cases.splice(index, 1);
};

const addIntegration = () => {
    if (newIntegration.value.trim()) {
        props.form.integrations.push(newIntegration.value.trim());
        newIntegration.value = '';
    }
};

const removeIntegration = (index: number) => {
    props.form.integrations.splice(index, 1);
};

const handleScreenshotUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        props.form.screenshot = file;

        // Create preview URL
        const reader = new FileReader();
        reader.onload = (e) => {
            screenshotPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <form @submit.prevent="emit('submit')" class="space-y-6">
        <!-- Basic Information -->
        <Card>
            <CardHeader>
                <CardTitle>Basic Information</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="name">Tool Name *</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="e.g., GitHub Copilot"
                        />
                        <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="category_id">Category *</Label>
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            required
                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                        >
                            <option value="">Select a category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id.toString()">
                                {{ cat.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="text-sm text-destructive">{{ form.errors.category_id }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="slug">Slug</Label>
                    <Input
                        id="slug"
                        v-model="form.slug"
                        type="text"
                        placeholder="Auto-generated from name if left empty"
                    />
                    <p class="text-xs text-muted-foreground">Used in URLs. Leave empty to auto-generate.</p>
                    <p v-if="form.errors.slug" class="text-sm text-destructive">{{ form.errors.slug }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="description">Short Description *</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        rows="2"
                        required
                        placeholder="Brief one-line description"
                    />
                    <p v-if="form.errors.description" class="text-sm text-destructive">{{ form.errors.description }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="long_description">Long Description</Label>
                    <Textarea
                        id="long_description"
                        v-model="form.long_description"
                        rows="4"
                        placeholder="Detailed description of the tool"
                    />
                    <p v-if="form.errors.long_description" class="text-sm text-destructive">{{ form.errors.long_description }}</p>
                </div>
            </CardContent>
        </Card>

        <!-- Links -->
        <Card>
            <CardHeader>
                <CardTitle>Links & Resources</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="space-y-2">
                    <Label for="website_url">Website URL</Label>
                    <Input
                        id="website_url"
                        v-model="form.website_url"
                        type="url"
                        placeholder="https://example.com"
                    />
                    <p v-if="form.errors.website_url" class="text-sm text-destructive">{{ form.errors.website_url }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="documentation_url">Documentation URL</Label>
                    <Input
                        id="documentation_url"
                        v-model="form.documentation_url"
                        type="url"
                        placeholder="https://docs.example.com"
                    />
                    <p v-if="form.errors.documentation_url" class="text-sm text-destructive">{{ form.errors.documentation_url }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="logo_url">Logo URL</Label>
                    <Input
                        id="logo_url"
                        v-model="form.logo_url"
                        type="url"
                        placeholder="https://example.com/logo.png"
                    />
                    <p v-if="form.errors.logo_url" class="text-sm text-destructive">{{ form.errors.logo_url }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="screenshot">Screenshot</Label>

                    <!-- File Upload -->
                    <div class="space-y-2">
                        <Input
                            id="screenshot"
                            type="file"
                            accept="image/*"
                            @change="handleScreenshotUpload"
                        />
                        <p class="text-xs text-muted-foreground">Upload a screenshot of the tool interface (JPG, PNG, WebP)</p>
                    </div>

                    <!-- OR URL Input -->
                    <div class="space-y-2">
                        <Label for="screenshot_url" class="text-xs">Or provide a URL:</Label>
                        <Input
                            id="screenshot_url"
                            v-model="form.screenshot_url"
                            type="url"
                            placeholder="https://example.com/screenshot.png"
                        />
                    </div>

                    <!-- Current Screenshot Preview -->
                    <div v-if="form.screenshot_url && !screenshotPreview" class="mt-2">
                        <p class="text-xs text-muted-foreground mb-1">Current screenshot:</p>
                        <img :src="form.screenshot_url" alt="Current screenshot" class="max-w-xs rounded border" />
                    </div>

                    <!-- New Screenshot Preview -->
                    <div v-if="screenshotPreview" class="mt-2">
                        <p class="text-xs text-muted-foreground mb-1">New screenshot preview:</p>
                        <img :src="screenshotPreview" alt="Screenshot preview" class="max-w-xs rounded border" />
                    </div>

                    <p v-if="form.errors.screenshot_url" class="text-sm text-destructive">{{ form.errors.screenshot_url }}</p>
                    <p v-if="form.errors.screenshot" class="text-sm text-destructive">{{ form.errors.screenshot }}</p>
                </div>
            </CardContent>
        </Card>

        <!-- Pricing -->
        <Card>
            <CardHeader>
                <CardTitle>Pricing</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="space-y-2">
                    <Label for="pricing_model">Pricing Model *</Label>
                    <select
                        id="pricing_model"
                        v-model="form.pricing_model"
                        required
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        <option value="">Select pricing model</option>
                        <option value="free">Free</option>
                        <option value="freemium">Freemium</option>
                        <option value="paid">Paid</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                    <p v-if="form.errors.pricing_model" class="text-sm text-destructive">{{ form.errors.pricing_model }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="price_description">Price Description</Label>
                    <Input
                        id="price_description"
                        v-model="form.price_description"
                        type="text"
                        placeholder="e.g., $10/month or Free with limits"
                    />
                    <p v-if="form.errors.price_description" class="text-sm text-destructive">{{ form.errors.price_description }}</p>
                </div>
            </CardContent>
        </Card>

        <!-- Ryan's Review -->
        <Card>
            <CardHeader>
                <CardTitle>Personal Review</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="ryan_rating">Rating (1-10)</Label>
                        <Input
                            id="ryan_rating"
                            v-model.number="form.ryan_rating"
                            type="number"
                            min="1"
                            max="10"
                            placeholder="1-10"
                        />
                        <p v-if="form.errors.ryan_rating" class="text-sm text-destructive">{{ form.errors.ryan_rating }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="ryan_last_used">Last Used</Label>
                        <Input
                            id="ryan_last_used"
                            v-model="form.ryan_last_used"
                            type="date"
                        />
                        <p v-if="form.errors.ryan_last_used" class="text-sm text-destructive">{{ form.errors.ryan_last_used }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="ryan_notes">Personal Notes</Label>
                    <Textarea
                        id="ryan_notes"
                        v-model="form.ryan_notes"
                        rows="3"
                        placeholder="Your experience and thoughts about this tool"
                    />
                    <p v-if="form.errors.ryan_notes" class="text-sm text-destructive">{{ form.errors.ryan_notes }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="first_reviewed_at">First Reviewed</Label>
                    <Input
                        id="first_reviewed_at"
                        v-model="form.first_reviewed_at"
                        type="date"
                    />
                    <p v-if="form.errors.first_reviewed_at" class="text-sm text-destructive">{{ form.errors.first_reviewed_at }}</p>
                </div>
            </CardContent>
        </Card>

        <!-- Features & Details -->
        <Card>
            <CardHeader>
                <CardTitle>Features & Details</CardTitle>
            </CardHeader>
            <CardContent class="space-y-6">
                <!-- Features -->
                <div class="space-y-2">
                    <Label>Features</Label>
                    <div class="flex gap-2">
                        <Input
                            v-model="newFeature"
                            type="text"
                            placeholder="Add a feature"
                            @keyup.enter="addFeature"
                        />
                        <Button type="button" @click="addFeature" size="sm">
                            <Plus class="h-4 w-4" />
                        </Button>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div v-for="(feature, index) in form.features" :key="index" class="flex items-center gap-1 bg-muted px-3 py-1 rounded-md">
                            <span class="text-sm">{{ feature }}</span>
                            <Button type="button" variant="ghost" size="sm" @click="removeFeature(index)" class="h-auto p-1">
                                <X class="h-3 w-3" />
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Use Cases -->
                <div class="space-y-2">
                    <Label>Use Cases</Label>
                    <div class="flex gap-2">
                        <Input
                            v-model="newUseCase"
                            type="text"
                            placeholder="Add a use case"
                            @keyup.enter="addUseCase"
                        />
                        <Button type="button" @click="addUseCase" size="sm">
                            <Plus class="h-4 w-4" />
                        </Button>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div v-for="(useCase, index) in form.use_cases" :key="index" class="flex items-center gap-1 bg-muted px-3 py-1 rounded-md">
                            <span class="text-sm">{{ useCase }}</span>
                            <Button type="button" variant="ghost" size="sm" @click="removeUseCase(index)" class="h-auto p-1">
                                <X class="h-3 w-3" />
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Integrations -->
                <div class="space-y-2">
                    <Label>Integrations</Label>
                    <div class="flex gap-2">
                        <Input
                            v-model="newIntegration"
                            type="text"
                            placeholder="Add an integration"
                            @keyup.enter="addIntegration"
                        />
                        <Button type="button" @click="addIntegration" size="sm">
                            <Plus class="h-4 w-4" />
                        </Button>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div v-for="(integration, index) in form.integrations" :key="index" class="flex items-center gap-1 bg-muted px-3 py-1 rounded-md">
                            <span class="text-sm">{{ integration }}</span>
                            <Button type="button" variant="ghost" size="sm" @click="removeIntegration(index)" class="h-auto p-1">
                                <X class="h-3 w-3" />
                            </Button>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Status -->
        <Card>
            <CardHeader>
                <CardTitle>Status & Visibility</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="flex items-center space-x-2">
                    <Checkbox id="is_featured" v-model:checked="form.is_featured" />
                    <Label for="is_featured" class="cursor-pointer">Featured (show on homepage)</Label>
                </div>

                <div class="flex items-center space-x-2">
                    <Checkbox id="is_active" v-model:checked="form.is_active" />
                    <Label for="is_active" class="cursor-pointer">Active (visible on public site)</Label>
                </div>
            </CardContent>
        </Card>

        <!-- Vote Counts -->
        <Card>
            <CardHeader>
                <CardTitle>Vote Counts (Admin Override)</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <p class="text-sm text-muted-foreground">
                    Manually adjust vote counts if needed to moderate voting activity.
                </p>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="upvotes">Upvotes</Label>
                        <Input
                            id="upvotes"
                            v-model.number="form.upvotes"
                            type="number"
                            min="0"
                            placeholder="0"
                        />
                        <p v-if="form.errors.upvotes" class="text-sm text-destructive">{{ form.errors.upvotes }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="downvotes">Downvotes</Label>
                        <Input
                            id="downvotes"
                            v-model.number="form.downvotes"
                            type="number"
                            min="0"
                            placeholder="0"
                        />
                        <p v-if="form.errors.downvotes" class="text-sm text-destructive">{{ form.errors.downvotes }}</p>
                    </div>
                </div>
                <div class="text-sm text-muted-foreground">
                    Net Score: {{ form.upvotes - form.downvotes }}
                </div>
            </CardContent>
        </Card>

        <!-- Actions -->
        <div class="flex items-center gap-4">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
            <Link :href="'/admin/tools'">
                <Button type="button" variant="outline">
                    Cancel
                </Button>
            </Link>
        </div>
    </form>
</template>
