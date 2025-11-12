<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Shield, Lock } from 'lucide-vue-next';
</script>

<template>
    <AuthBase
        title="Sign the Manifesto and Create an Account"
        description="Join our community committed to responsible AI development"
    >
        <Head title="Sign the Manifesto" />

        <Form
            v-bind="RegisteredUserController.store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Full name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <!-- Privacy & Security Notice -->
                <Alert class="border-success/20 bg-success/5">
                    <Shield class="h-4 w-4 text-success" />
                    <AlertDescription class="text-sm space-y-1">
                        <ul class="space-y-1 ml-4 list-disc">
                            <li>We never sell your information</li>
                            <li>We never publicize or distribute your email address</li>
                            <li>Your account information is secure</li>
                        </ul>
                    </AlertDescription>
                </Alert>

                <!-- Manifesto Declaration -->
                <Alert class="border-info/20 bg-info/5">
                    <Lock class="h-4 w-4 text-info" />
                    <AlertDescription class="text-sm">
                        <p class="mb-2 font-semibold">By signing the manifesto, you declare that you:</p>
                        <ul class="space-y-1 ml-4 list-disc">
                            <li>Subscribe to the principles stated on the home page</li>
                            <li>Agree to help others and contribute to responsible AI development</li>
                        </ul>
                    </AlertDescription>
                </Alert>

                <!-- Newsletter Opt-in -->
                <div class="flex items-start space-x-3 rounded-lg border border-border p-4">
                    <Checkbox
                        id="newsletter"
                        name="newsletter"
                        :tabindex="5"
                        class="mt-0.5"
                    />
                    <div class="flex-1">
                        <Label
                            for="newsletter"
                            class="text-sm font-medium leading-none cursor-pointer"
                        >
                            Subscribe to newsletter
                        </Label>
                        <p class="text-xs text-muted-foreground mt-1.5">
                            Get updates on new AI tools, best practices, and community contributions
                        </p>
                    </div>
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="6"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="h-4 w-4 animate-spin"
                    />
                    Sign the Manifesto and Create Account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="7"
                    >Log in</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
