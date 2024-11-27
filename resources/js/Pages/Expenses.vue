<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Form data and state management
const expenseForm = useForm({
    category_id: '', // Category ID for the dropdown
    amount: '',
    description: '',
    expense_date: '',
});

const categories = ref([]); // To hold the category list
const expenses = ref([]); // To hold the list of expenses

// Fetch categories and expenses on component mount
onMounted(() => {
    fetchCategories();
    fetchExpenses();
});

// Fetch categories from the API
const fetchCategories = async () => {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();
        categories.value = data.data; // Assuming response has 'data'
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

// Fetch expenses from the API
const fetchExpenses = async () => {
    try {
        const response = await fetch('/api/expenses');
        const data = await response.json();
        expenses.value = data.data; // Assuming response has 'data'
    } catch (error) {
        console.error('Error fetching expenses:', error);
    }
};

// Handle adding a new expense
const addExpense = () => {
    expenseForm.post('/api/expenses', {
        onFinish: () => {
            expenseForm.reset(); // Reset form fields after submission
            fetchExpenses(); // Refresh the expenses list after adding a new one
        },
    });
};
</script>

<template>
    <Head title="Expenses" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Expenses</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Form to Add Expense -->
                <div class="bg-white shadow-sm sm:rounded-lg mb-8 p-6">
                    <h3 class="text-lg font-medium mb-4">Add New Expense</h3>
                    <form @submit.prevent="addExpense">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Category Dropdown -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select
                                    v-model="expenseForm.category_id"
                                    id="category"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="" disabled>Select Category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Amount Field -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input
                                    v-model="expenseForm.amount"
                                    id="amount"
                                    type="number"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="e.g., 1000"
                                    required
                                />
                            </div>

                            <!-- Expense Date Field -->
                            <div>
                                <label for="expense_date" class="block text-sm font-medium text-gray-700">Expense Date</label>
                                <input
                                    v-model="expenseForm.expense_date"
                                    id="expense_date"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    required
                                />
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <input
                                    v-model="expenseForm.description"
                                    id="description"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="e.g., Groceries"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            :class="{ 'opacity-50': expenseForm.processing }"
                            :disabled="expenseForm.processing"
                        >
                            Add Expense
                        </button>
                    </form>
                </div>

                <!-- Expenses Table -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-medium mb-4 p-6">Expenses</h3>
                    <table class="table-auto w-full text-left border-collapse border border-gray-200">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 border border-gray-300">Category</th>
                            <th class="px-6 py-3 border border-gray-300">Amount</th>
                            <th class="px-6 py-3 border border-gray-300">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="expense in expenses" :key="expense.id">
                            <td class="px-6 py-4 border border-gray-300">{{ expense.category.name }}</td>
                            <td class="px-6 py-4 border border-gray-300">${{ expense.amount }}</td>
                            <td class="px-6 py-4 border border-gray-300">{{ expense.expense_date }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
