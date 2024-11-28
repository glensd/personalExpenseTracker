<script setup>
import { ref, onMounted } from 'vue';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, PieController, BarController, CategoryScale, LinearScale, BarElement } from 'chart.js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

ChartJS.register(Title, Tooltip, Legend, ArcElement, PieController, BarController, CategoryScale, LinearScale, BarElement);

const pieChartCanvas = ref(null);
const barChartCanvas = ref(null);

const summary = ref({
    totalAmount: 0,
    totalCategories: 0,
    totalExpenses: 0,
    averageExpense: 0,
});

const pieChartData = ref({
    labels: [],
    datasets: [
        {
            data: [],
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
            ],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverBackgroundColor: [
                '#FF7B9E',
                '#58B3F0',
                '#FFD76E',
                '#67D4D4',
                '#B584FF',
                '#FFB660',
            ],
            hoverBorderColor: '#000000',
            hoverBorderWidth: 3,
        },
    ],
});

const barChartData = ref({
    labels: [],
    datasets: [
        {
            label: 'Expenses',
            data: [],
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
            ],
            borderColor: '#333333',
            borderRadius: 5,
            hoverBackgroundColor: '#FFD700',
        },
    ],
});


const pieChartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                font: {
                    size: 14,

                },
                color: '#2D3748',
            },
        },
        tooltip: {
            backgroundColor: '#1A202C',
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 12 },
            borderWidth: 1,
            borderColor: '#CBD5E0',
        },
    },
    animation: {
        animateRotate: true,
        animateScale: true,
    },
};

const barChartOptions = {
    responsive: true,
    scales: {
        x: {
            title: {
                display: true,
                text: 'Months',
                font: {
                    size: 14,
                    weight: 'bold',
                },
                color: '#4A5568',
            },
            grid: {
                display: false,
            },
        },
        y: {
            title: {
                display: true,
                text: 'Amount ($)',
                font: {
                    size: 14,
                    weight: 'bold',
                },
                color: '#4A5568',
            },
            grid: {
                color: '#E2E8F0',
                borderDash: [5, 5],
            },
            beginAtZero: true,
        },
    },
    plugins: {
        legend: {
            position: 'top',
            labels: {
                font: {
                    size: 12,
                },
                color: '#2D3748',
            },
        },
        tooltip: {
            backgroundColor: '#1A202C',
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 12 },
            borderWidth: 1,
            borderColor: '#CBD5E0',
        },
    },
};

const fetchAnalyticsData = async () => {
    try {
        const response = await axios.get('/api/analytics');
        const { categoryData, monthlyData, summary: summaryData } = response.data;

        summary.value = {
            totalAmount: Number(summaryData.totalAmount) || 0,
            totalCategories: Number(summaryData.totalCategories) || 0,
            totalExpenses: Number(summaryData.totalExpenses) || 0,
            averageExpense: Number(summaryData.averageExpense) || 0,
        };

        pieChartData.value.labels = categoryData.map((item) => item.category);
        pieChartData.value.datasets[0].data = categoryData.map((item) => item.total);
        pieChartData.value.datasets[0].backgroundColor = categoryData.map(() =>
            `#${Math.floor(Math.random() * 16777215).toString(16)}`
        );

        barChartData.value.labels = monthlyData.map((item) => item.month);
        barChartData.value.datasets[0].data = monthlyData.map((item) => item.total);
    } catch (error) {
        console.error('Error fetching analytics data:', error);
    }
};

onMounted(() => {
    fetchAnalyticsData().then(() => {
        if (pieChartCanvas.value) {
            new ChartJS(pieChartCanvas.value.getContext('2d'), {
                type: 'pie',
                data: pieChartData.value,
                options: pieChartOptions,
            });
        }
        if (barChartCanvas.value) {
            new ChartJS(barChartCanvas.value.getContext('2d'), {
                type: 'bar',
                data: barChartData.value,
                options: barChartOptions,
            });
        }
    });
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Analytics</h2>
        </template>

        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-800">Total Amount</h3>
                        <p class="text-2xl font-bold text-gray-600">
                            {{ typeof summary.totalAmount === 'number' ? summary.totalAmount.toFixed(2) : '0.00' }}
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-800">Total Categories</h3>
                        <p class="text-2xl font-bold text-gray-600">{{ summary.totalCategories }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-800">Total Expenses</h3>
                        <p class="text-2xl font-bold text-gray-600">{{ summary.totalExpenses }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-800">Average Expense</h3>
                        <p class="text-2xl font-bold text-gray-600">
                            {{ typeof summary.averageExpense === 'number' ? summary.averageExpense.toFixed(2) : '0.00' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-center mb-4">Expenses by Category</h3>
                        <canvas ref="pieChartCanvas" style="width: 100%; max-width: 400px; margin: auto;"></canvas>
                    </div>
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-center mb-4">Monthly Expenses</h3>
                        <canvas ref="barChartCanvas" style="width: 100%; max-width: 400px; margin: auto;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
