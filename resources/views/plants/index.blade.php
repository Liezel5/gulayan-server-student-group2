<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plants Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="container mx-auto p-6 lg:p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold mb-2">Plants Management</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage and view all plants in the system</p>
        </div>

        <!-- Plants Table -->
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-gray-200 dark:border-[#3E3E3A] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-[#3E3E3A] bg-gray-50 dark:bg-[#1D1D1D]">
                            <th class="px-6 py-4 text-left font-semibold">ID</th>
                            <th class="px-6 py-4 text-left font-semibold">Name</th>
                            <th class="px-6 py-4 text-left font-semibold">Variety</th>
                            <th class="px-6 py-4 text-left font-semibold">Batch Name</th>
                            <th class="px-6 py-4 text-left font-semibold">Date Planted</th>
                            <th class="px-6 py-4 text-left font-semibold">Seedling Count</th>
                            <th class="px-6 py-4 text-left font-semibold">Starting Fund</th>
                            <th class="px-6 py-4 text-left font-semibold">Notes</th>
                        </tr>
                    </thead>
                    <tbody id="plants-table-body">
                        <tr class="border-t border-gray-200 dark:border-[#3E3E3A]">
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex items-center justify-center">
                                    <div class="w-5 h-5 border-2 border-gray-300 border-t-[#f53003] rounded-full animate-spin"></div>
                                    <span class="ml-3">Loading plants...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="border-t border-gray-200 dark:border-[#3E3E3A] px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <label for="per-page" class="text-sm font-medium">Per page:</label>
                    <select id="per-page" class="px-3 py-2 border border-gray-300 dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#1D1D1D] text-sm">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing <span id="from">0</span> to <span id="to">0</span> of <span id="total">0</span> results
                </div>

                <div class="flex items-center gap-2">
                    <button id="prev-btn" class="px-4 py-2 border border-gray-300 dark:border-[#3E3E3A] rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#1D1D1D] disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
                        Previous
                    </button>
                    
                    <div id="page-numbers" class="flex items-center gap-1">
                        <!-- Page numbers will be inserted here -->
                    </div>
                    
                    <button id="next-btn" class="px-4 py-2 border border-gray-300 dark:border-[#3E3E3A] rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#1D1D1D] disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let perPage = 10;
        let isLoading = false;

        async function fetchPlants(page = 1, itemsPerPage = 10) {
            if (isLoading) return;
            
            isLoading = true;
            const tableBody = document.getElementById('plants-table-body');
            
            try {
                const response = await fetch(`/api/plants?page=${page}&per_page=${itemsPerPage}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const jsonData = await response.json();
                
                if (jsonData.success) {
                    renderTable(jsonData.data);
                    updatePagination(jsonData.pagination);
                } else {
                    showError('Failed to load plants data');
                }
            } catch (error) {
                console.error('Error fetching plants:', error);
                showError(`Error loading plants: ${error.message}`);
            } finally {
                isLoading = false;
            }
        }

        function renderTable(plants) {
            const tbody = document.getElementById('plants-table-body');
            
            if (plants.length === 0) {
                tbody.innerHTML = `
                    <tr class="border-t border-gray-200 dark:border-[#3E3E3A]">
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            No plants found
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = plants.map(plant => `
                <tr class="border-t border-gray-200 dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-[#1D1D1D] transition-colors">
                    <td class="px-6 py-4">${plant.id}</td>
                    <td class="px-6 py-4 font-medium">${escapeHtml(plant.name || '-')}</td>
                    <td class="px-6 py-4">${escapeHtml(plant.variety || '-')}</td>
                    <td class="px-6 py-4">${escapeHtml(plant.batch_name || '-')}</td>
                    <td class="px-6 py-4">${plant.date_planted ? new Date(plant.date_planted).toLocaleDateString() : '-'}</td>
                    <td class="px-6 py-4">${plant.seedling_count || '-'}</td>
                    <td class="px-6 py-4">${plant.starting_fund ? '$' + parseFloat(plant.starting_fund).toFixed(2) : '-'}</td>
                    <td class="px-6 py-4 max-w-xs truncate" title="${escapeHtml(plant.notes || '')}">${escapeHtml(plant.notes || '-')}</td>
                </tr>
            `).join('');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            const tbody = document.getElementById('plants-table-body');
            tbody.innerHTML = `
                <tr class="border-t border-gray-200 dark:border-[#3E3E3A]">
                    <td colspan="8" class="px-6 py-8">
                        <div class="flex items-center justify-center bg-red-50 dark:bg-[#1D0002] rounded-md p-4 border border-red-200 dark:border-[#4B0600]">
                            <svg class="w-5 h-5 text-red-600 dark:text-[#FF4433] mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-red-800 dark:text-[#FF4433]">${escapeHtml(message)}</span>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updatePagination(pagination) {
            currentPage = pagination.current_page;
            perPage = pagination.per_page;

            // Update info text
            document.getElementById('from').textContent = pagination.from || 0;
            document.getElementById('to').textContent = pagination.to || 0;
            document.getElementById('total').textContent = pagination.total;

            // Update prev/next buttons
            document.getElementById('prev-btn').disabled = currentPage === 1;
            document.getElementById('next-btn').disabled = currentPage === pagination.last_page;

            // Generate page numbers
            const pageNumbersContainer = document.getElementById('page-numbers');
            pageNumbersContainer.innerHTML = '';

            const maxPages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
            let endPage = Math.min(pagination.last_page, startPage + maxPages - 1);

            if (endPage - startPage + 1 < maxPages) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }

            for (let page = startPage; page <= endPage; page++) {
                const button = document.createElement('button');
                button.textContent = page;
                button.className = `px-3 py-2 rounded-md text-sm font-medium transition-colors ${
                    page === currentPage
                        ? 'bg-[#f53003] text-white'
                        : 'border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-[#1D1D1D]'
                }`;
                button.addEventListener('click', () => {
                    fetchPlants(page, perPage);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
                pageNumbersContainer.appendChild(button);
            }
        }

        // Event listeners
        document.getElementById('per-page').addEventListener('change', (e) => {
            perPage = parseInt(e.target.value);
            fetchPlants(1, perPage);
        });

        document.getElementById('prev-btn').addEventListener('click', () => {
            if (currentPage > 1) {
                fetchPlants(currentPage - 1, perPage);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        document.getElementById('next-btn').addEventListener('click', () => {
            fetchPlants(currentPage + 1, perPage);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Initial load
        fetchPlants(1, perPage);
    </script>
</body>
</html>
