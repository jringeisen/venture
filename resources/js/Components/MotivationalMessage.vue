<template>
    <div>
        <Modal :show="true">
            <div class="p-8 prose">
                <h1 class="text-2xl font-bold text-center">Motivation For The Day</h1>
                <p class="text-center py-4">
                    {{ message }}
                </p>
                <div class="flex justify-center">
                    <button
                        @click.prevent="updateUser()"
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none"
                    >
                        Start The Day
                    </button>
                </div>
            </div>
        </Modal>
        <div class="absolute left-1/2 top-1/2">
            <ConfettiExplosion :particleCount="300" :force=".5" />
        </div>
    </div>
</template>

<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import ConfettiExplosion from "vue-confetti-explosion";
import Modal from '@/Components/Modal.vue';

defineProps({
    message: {
        type: String,
        default: ''
    }
});

const page = usePage();

const form = useForm({
    motivational_message: new Date().toISOString(),
    redirect_route: 'student.dashboard',
});

const updateUser = () => {
    form.patch(route('student.students.update', page.props.auth.user));
}
</script>
