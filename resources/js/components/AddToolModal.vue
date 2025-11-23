<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { AlertCircle, CheckCircle2, Loader2, Sparkles } from 'lucide-vue-next';

const props = defineProps({
    open: Boolean,
});

const emit = defineEmits(['update:open']);

const form = useForm({
    user_input: '',
});

const success = ref(false);
const successMessage = ref('');
const error = ref('');

const submit = () => {
    error.value = '';
    success.value = false;

    axios.post('/tools/request', {
        user_input: form.user_input
    })
    .then(response => {
        success.value = true;
        successMessage.value = response.data.message;
        form.reset();
        // Close modal after delay
        setTimeout(() => {
            emit('update:open', false);
            success.value = false;
        }, 3000);
    })
    .catch(err => {
        if (err.response && err.response.data) {
            error.value = err.response.data.message || 'Something went wrong.';
        } else {
            error.value = 'An unexpected error occurred.';
        }
    });
};

const close = () => {
    emit('update:open', false);
    form.reset();
    error.value = '';
    success.value = false;
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <Sparkles class="w-5 h-5 text-primary" />
                    Add New Tool
                </DialogTitle>
                <DialogDescription>
                    Submit a tool for our AI to research and add to the directory.
                </DialogDescription>
            </DialogHeader>

            <div v-if="success" class="py-6 text-center">
                <div class="flex justify-center mb-4">
                    <CheckCircle2 class="w-12 h-12 text-green-500" />
                </div>
                <h3 class="text-lg font-semibold text-foreground mb-2">Request Received!</h3>
                <p class="text-muted-foreground">{{ successMessage }}</p>
            </div>

            <form v-else @submit.prevent="submit" class="space-y-6 py-4">
                <div class="space-y-2">
                    <Label for="description">Tool Description</Label>
                    <Textarea
                        id="description"
                        v-model="form.user_input"
                        placeholder="e.g. Obsidian - A powerful knowledge base that works on top of a local folder of plain text Markdown files."
                        class="min-h-[120px]"
                        required
                    />
                    <p class="text-xs text-muted-foreground">
                        Provide the name and a brief description or URL.
                    </p>
                </div>

                <!-- How it works -->
                <div class="bg-muted/50 rounded-lg p-4 text-sm space-y-2">
                    <p class="font-medium text-foreground">How this works:</p>
                    <ul class="list-disc list-inside text-muted-foreground space-y-1">
                        <li>Our AI validates your request instantly.</li>
                        <li>If approved, we launch a deep research agent.</li>
                        <li>The tool is added to the directory automatically.</li>
                    </ul>
                </div>

                <div v-if="error" class="flex items-center gap-2 text-sm text-destructive bg-destructive/10 p-3 rounded-md">
                    <AlertCircle class="w-4 h-4" />
                    {{ error }}
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="close">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing || !form.user_input">
                        <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                        {{ form.processing ? 'Validating...' : 'Submit Request' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
