<script>
    import { onMount } from "svelte";
    import {
        Users,
        FileText,
        Trophy,
        Image as ImageIcon,
        TrendingUp,
        Calendar,
    } from "lucide-svelte";
    import Card from "../../components/Card.svelte";
    import api from "../../services/axios";
    import Chart from "chart.js/auto";

    let statsData = $state(null);
    let chartCanvas = $state(null);
    let chartInstance = null;

    let stats = $derived([
        {
            title: "Total Guru",
            value: statsData?.total_guru || "0",
            icon: Users,
            color: "bg-blue-500 text-blue-500",
        },
        {
            title: "Total Berita",
            value: statsData?.total_berita || "0",
            icon: FileText,
            color: "bg-green-500 text-green-500",
        },
        {
            title: "Total Prestasi",
            value: statsData?.total_prestasi || "0",
            icon: Trophy,
            color: "bg-yellow-500 text-yellow-500",
        },
        {
            title: "Total Galeri",
            value: statsData?.total_galeri || "0",
            icon: ImageIcon,
            color: "bg-purple-500 text-purple-500",
        },
    ]);

    const recentActivities = [
        {
            user: "Admin",
            action: "menambahkan berita baru",
            time: "2 jam yang lalu",
        },
        {
            user: "Admin",
            action: "mengubah data PPDB",
            time: "5 jam yang lalu",
        },
        {
            user: "Admin",
            action: "menambahkan foto galeri",
            time: "1 hari yang lalu",
        },
        {
            user: "Admin",
            action: "memperbarui profil guru",
            time: "2 hari yang lalu",
        },
    ];

    const initChart = (data) => {
        if (chartInstance) chartInstance.destroy();
        if (!chartCanvas) return;

        const ctx = chartCanvas.getContext("2d");

        const rawLabels = data.chart?.labels || [
            "H-6",
            "H-5",
            "H-4",
            "H-3",
            "H-2",
            "H-1",
            "Hari Ini",
        ];
        const rawValues = data.chart?.data || [0, 0, 0, 0, 0, 0, 0];

        // Clone the arrays to remove Svelte 5 reactive proxies, which conflict with Chart.js mutations
        const labels = [...rawLabels];
        const values = [...rawValues];

        chartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Perubahan Data Baru",
                        data: values,
                        borderColor: "#0D8A4E",
                        backgroundColor: "rgba(13, 138, 78, 0.1)",
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: "#0D8A4E",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                    },
                    x: {
                        grid: { display: false },
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "rgba(17, 24, 39, 0.9)",
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                    },
                },
                interaction: {
                    mode: "index",
                    intersect: false,
                },
            },
        });
    };

    onMount(async () => {
        try {
            const response = await api.get("/v1/admin/dashboard");
            statsData = response.data;
            setTimeout(() => {
                initChart(statsData);
            }, 50);
        } catch (error) {
            console.error("Error fetching dashboard data:", error);
        }
    });
</script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text-title font-heading">
                Dashboard Overview
            </h1>
            <p class="text-text-body text-sm mt-1">
                Ringkasan statistik website Pesantren Pondok Pesantren Ulul
                Albab
            </p>
        </div>
        <div
            class="hidden sm:flex items-center gap-2 text-sm text-text-body bg-white px-4 py-2 rounded-xl shadow-sm border border-border-color"
        >
            <Calendar size={16} />
            {new Date().toLocaleDateString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            })}
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {#each stats as stat}
            <Card class="flex items-center p-6 gap-4">
                <div
                    class="w-14 h-14 rounded-2xl flex items-center justify-center bg-opacity-10 {stat.color.split(
                        ' ',
                    )[0]}/10"
                >
                    <stat.icon size={28} class={stat.color.split(" ")[1]} />
                </div>
                <div>
                    <p class="text-sm font-medium text-text-body">
                        {stat.title}
                    </p>
                    <h3 class="text-2xl font-bold text-text-title mt-1">
                        {stat.value}
                    </h3>
                </div>
            </Card>
        {/each}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Placeholder -->
        <Card class="lg:col-span-2 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-lg font-bold text-text-title">
                    Statistik Pertumbuhan Data
                </h2>
                <div
                    class="flex items-center gap-1 text-sm text-green-600 font-medium bg-green-50 px-3 py-1 rounded-full"
                >
                    <TrendingUp size={16} />
                    <span>Realtime</span>
                </div>
            </div>
            <div
                class="h-64 relative bg-bg-section rounded-xl border border-border-color p-4"
            >
                <canvas bind:this={chartCanvas}></canvas>
            </div>
        </Card>

        <!-- Recent Activity -->
        <Card class="p-6">
            <h2 class="text-lg font-bold text-text-title mb-6">
                Aktivitas Terbaru
            </h2>
            <div class="space-y-6">
                {#each recentActivities as activity}
                    <div class="flex gap-4 relative">
                        <div
                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold z-10"
                        >
                            A
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-sm text-text-title">
                                <span class="font-bold">{activity.user}</span>
                                {activity.action}
                            </p>
                            <p class="text-xs text-text-body mt-1">
                                {activity.time}
                            </p>
                        </div>
                    </div>
                {/each}
            </div>
        </Card>
    </div>
</div>
