<script setup>
import { ref, onMounted, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const expenseForm = useForm({
    category_id: '',
    amount: '',
    description: '',
    expense_date: '',
});

const categoryFilter = ref('');
const startDate = ref('');
const endDate = ref('');
const categories = ref([]);
const expenses = ref([]);
const successMessage = ref('');
const currentPage = ref(1);
const perPage = ref(10);
const perPageOptions = [5, 10, 15, 20];

const paginatedExpenses = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return expenses.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(expenses.value.length / perPage.value));

onMounted(() => {
    fetchCategories();
    fetchExpenses();
});
const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const fetchExpenses = async () => {
    const queryParams = new URLSearchParams();
    if (categoryFilter.value) queryParams.append('category_id', categoryFilter.value);
    if (startDate.value) queryParams.append('start_date', startDate.value);
    if (endDate.value) queryParams.append('end_date', endDate.value);

    try {
        const response = await axios.get(`/api/expenses?${queryParams.toString()}`);
        expenses.value = response.data.data;
    } catch (error) {
        console.error('Error fetching expenses:', error);
    }
};

const addExpense = async () => {
    try {
        expenseForm.processing = true;
        const response = await axios.post('/api/expenses', expenseForm.data());
        expenses.value.unshift(response.data.data);
        expenseForm.reset();

        successMessage.value = 'Expense added successfully!';
        setTimeout(() => {
            successMessage.value = '';
        }, 6000);
    } catch (error) {
        console.error('Error adding expense:', error.response?.data || error.message);
    } finally {
        expenseForm.processing = false;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

const previousPage = () => {
    if (currentPage.value > 1) currentPage.value--;
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
                <div v-if="successMessage" class="bg-green-100 text-green-700 p-4 rounded-md mb-4">
                    {{ successMessage }}
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg mb-8 p-6">
                    <h3 class="text-lg font-medium mb-4">Add New Expense</h3>
                    <form @submit.prevent="addExpense">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                <div class="bg-white shadow-sm sm:rounded-lg mb-8 p-6">
                    <h3 class="text-lg font-medium mb-4">Filter Expenses</h3>
                    <form @submit.prevent="fetchExpenses">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Category Filter -->
                            <div>
                                <label for="categoryFilter" class="block text-sm font-medium text-gray-700">Category</label>
                                <select
                                    v-model="categoryFilter"
                                    id="categoryFilter"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                >
                                    <option value="">All Categories</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Start Date Filter -->
                            <div>
                                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input
                                    v-model="startDate"
                                    id="startDate"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                            </div>

                            <!-- End Date Filter -->
                            <div>
                                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input
                                    v-model="endDate"
                                    id="endDate"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                        >
                            Apply Filters
                        </button>
                    </form>
                </div>

                <!-- Expenses Table -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-auto max-h-96">
                    <h3 class="text-lg font-medium mb-4 p-6">Expenses</h3>
                    <table class="table-auto w-full text-left border-collapse border border-gray-200">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 border border-gray-300">Sr No</th>
                            <th class="px-6 py-3 border border-gray-300">Category</th>
                            <th class="px-6 py-3 border border-gray-300">Amount</th>
                            <th class="px-6 py-3 border border-gray-300">Date</th>
                            <th class="px-6 py-3 border border-gray-300">Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(expense, index) in paginatedExpenses" :key="expense.id">
                            <td class="px-6 py-4 border border-gray-300">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                            <td class="px-6 py-4 border border-gray-300">{{ expense.category.name }}</td>
                            <td class="px-6 py-4 border border-gray-300">${{ expense.amount }}</td>
                            <td class="px-6 py-4 border border-gray-300">{{ expense.expense_date }}</td>
                            <td class="px-6 py-4 border border-gray-300">{{ expense.description }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination and Records Per Page -->
                <div class="flex justify-between items-center mt-4">
                    <div>
                        <label for="perPage" class="text-sm text-gray-600">Records per page:</label>
                        <select
                            id="perPage"
                            v-model="perPage"
                            class="ml-2 p-1 border rounded-md"
                        >
                            <option v-for="option in perPageOptions" :key="option" :value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <button
                            @click="previousPage"
                            :disabled="currentPage === 1"
                            class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md"
                        >
                            Previous
                        </button>
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
