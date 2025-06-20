<?php
/**
 * Plugin Name: Manual Page Disconnector - Enhanced
 * Description: Visually disconnect pages between languages with detailed connection view
 * Version: 1.0.0
 * Author: Terrachad
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu
function manual_page_disconnector_menu() {
    add_submenu_page(
        'tools.php',
        'Manual Page Disconnector',
        'Manual Page Disconnector',
        'manage_options',
        'manual-page-disconnector',
        'manual_page_disconnector_page'
    );
}
add_action('admin_menu', 'manual_page_disconnector_menu');

// Admin page callback
function manual_page_disconnector_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Debug mode
    $debug_mode = isset($_POST['debug_mode']) || isset($_GET['debug']);
    
    // Display message from redirect
    $message = '';
    if (isset($_GET['message'])) {
        $message = sanitize_text_field(urldecode($_GET['message']));
    }
    
    // Process individual disconnection
    $debug_info = [];
    
    if (isset($_POST['disconnect_single'])) {
        $en_id = intval($_POST['en_id']);
        $it_id = intval($_POST['it_id']);
        
        $debug_info[] = "Disconnection request: EN=$en_id, IT=$it_id";
        
        if ($en_id > 0 && $it_id > 0) {
            $result = disconnect_single_page_pair($en_id, $it_id);
            $debug_info[] = "Disconnection result: " . ($result['success'] ? 'SUCCESS' : 'FAILED') . " - " . $result['message'];
            $message = $result['message'];
        } else {
            $message = "Invalid page IDs provided.";
        }
    }
    
    // Process bulk disconnection
    if (isset($_POST['disconnect_selected'])) {
        $debug_info[] = "Bulk disconnection requested";
        
        if (!empty($_POST['selected_connections'])) {
            $selected = $_POST['selected_connections'];
            $success = 0;
            $total = count($selected);
            
            foreach ($selected as $connection_key) {
                list($en_id, $it_id) = explode('-', $connection_key);
                $en_id = intval($en_id);
                $it_id = intval($it_id);
                
                if ($en_id > 0 && $it_id > 0) {
                    $result = disconnect_single_page_pair($en_id, $it_id);
                    if ($result['success']) {
                        $success++;
                    }
                }
            }
            
            $message = "Disconnected $success out of $total page pairs successfully.";
            $debug_info[] = "Bulk result: $message";
        } else {
            $message = "No connections selected for disconnection.";
        }
    }
    
    // Check if Polylang is active
    if (!function_exists('pll_get_post_translations') || !function_exists('pll_set_post_language')) {
        echo '<div class="notice notice-error"><p>Polylang is not active. Please activate Polylang first.</p></div>';
        return;
    }
    
    // Get all published pages
    $pages = get_pages(['post_status' => 'publish', 'sort_column' => 'post_title', 'number' => 1000]);
    
    // Get all existing connections
    $connections = get_all_connections($pages);
    
    ?>
    <div class="wrap">
        <h1>Manual Page Disconnector - Enhanced</h1>
        <p>View and disconnect page translations between languages.</p>
        
        <?php if (!empty($message)): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($debug_mode && !empty($debug_info)): ?>
            <div class="notice notice-info">
                <h4>Debug Information:</h4>
                <ul>
                    <?php foreach ($debug_info as $info): ?>
                        <li><?php echo esc_html($info); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="notice notice-info">
            <p><strong>Statistics:</strong> <?php echo count($connections); ?> page connections found</p>
        </div>
        
        <style>
        .disconnector-container {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .connection-header {
            background: #f5f5f5;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .connection-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        
        .connection-item:hover {
            background-color: #f9f9f9;
        }
        
        .connection-item:last-child {
            border-bottom: none;
        }
        
        .connection-checkbox {
            margin-right: 15px;
        }
        
        .page-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .language-section {
            flex: 1;
            min-width: 300px;
        }
        
        .language-flag {
            font-size: 20px;
            margin-right: 8px;
        }
        
        .page-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .page-meta {
            font-size: 12px;
            color: #666;
        }
        
        .connection-arrow {
            font-size: 24px;
            color: #0073aa;
            margin: 0 15px;
        }
        
        .disconnect-btn {
            background: #dc3232;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }
        
        .disconnect-btn:hover {
            background: #a00;
        }
        
        .brand-tag {
            background: #0073aa;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            margin-right: 5px;
        }
        
        .category-tag {
            background: #666;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            margin-right: 5px;
        }
        
        .filter-section {
            background: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            align-items: center;
            margin: 10px 0;
        }
        
        .bulk-actions {
            background: #f5f5f5;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .stats-bar {
            background: #dc3232;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 3px;
            margin: 10px 0;
        }
        
        .no-connections {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 16px;
        }
        
        .connection-details {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-link {
            color: #0073aa;
            text-decoration: none;
        }
        
        .page-link:hover {
            text-decoration: underline;
        }
        </style>
        
        <?php if (empty($connections)): ?>
            <div class="disconnector-container">
                <div class="no-connections">
                    <h3>🔗 No Connected Pages Found</h3>
                    <p>There are currently no pages connected between languages.</p>
                    <p>Use the Manual Page Connector to create connections first.</p>
                </div>
            </div>
        <?php else: ?>
            
            <div class="filter-section">
                <h3>Filters & Search</h3>
                <div class="filter-row">
                    <label>Brand:</label>
                    <select id="brand-filter">
                        <option value="">All Brands</option>
                    </select>
                    
                    <label>Category:</label>
                    <select id="category-filter">
                        <option value="">All Categories</option>
                    </select>
                    
                    <label>Search:</label>
                    <input type="text" id="search-filter" placeholder="Search page titles...">
                </div>
                
                <div class="filter-row">
                    <button type="button" id="clear-filters" class="button">Clear Filters</button>
                    <span id="filter-stats"></span>
                </div>
            </div>
            
            <div class="stats-bar">
                <span id="connection-count"><?php echo count($connections); ?> connected page pairs</span> | 
                <span id="filtered-count"><?php echo count($connections); ?> shown</span>
            </div>
            
            <form method="post" id="bulk-disconnect-form">
                <?php wp_nonce_field('manual_page_disconnector_action', 'manual_page_disconnector_nonce'); ?>
                <input type="hidden" name="debug_mode" value="1">
                
                <div class="bulk-actions">
                    <div class="filter-row">
                        <label>
                            <input type="checkbox" id="select-all"> Select All Visible
                        </label>
                        <button type="submit" name="disconnect_selected" class="button button-primary" id="bulk-disconnect-btn" disabled>
                            Disconnect Selected
                        </button>
                        <span id="selected-count">0 selected</span>
                    </div>
                </div>
                
                <div class="disconnector-container">
                    <div class="connection-header">
                        <h3>Connected Page Pairs</h3>
                        <span id="visible-count"><?php echo count($connections); ?> connections</span>
                    </div>
                    
                    <div id="connections-list">
                        <?php foreach ($connections as $index => $connection): ?>
                            <div class="connection-item" 
                                 data-brand-en="<?php echo esc_attr($connection['en_brand']); ?>"
                                 data-brand-it="<?php echo esc_attr($connection['it_brand']); ?>"
                                 data-category-en="<?php echo esc_attr($connection['en_category']); ?>"
                                 data-category-it="<?php echo esc_attr($connection['it_category']); ?>"
                                 data-title-en="<?php echo esc_attr(strtolower($connection['en_title'])); ?>"
                                 data-title-it="<?php echo esc_attr(strtolower($connection['it_title'])); ?>">
                                
                                <div class="connection-checkbox">
                                    <input type="checkbox" name="selected_connections[]" 
                                           value="<?php echo $connection['en_id'] . '-' . $connection['it_id']; ?>"
                                           class="connection-select">
                                </div>
                                
                                <div class="page-info">
                                    <div class="language-section">
                                        <div class="connection-details">
                                            <span class="language-flag">🇺🇸</span>
                                            <div>
                                                <div class="page-title">
                                                    <a href="<?php echo esc_url(get_edit_post_link($connection['en_id'])); ?>" 
                                                       class="page-link" target="_blank">
                                                        <?php echo esc_html($connection['en_title']); ?>
                                                    </a>
                                                </div>
                                                <div class="page-meta">
                                                    ID: <?php echo $connection['en_id']; ?>
                                                    <?php if ($connection['en_brand']): ?>
                                                        <span class="brand-tag"><?php echo esc_html($connection['en_brand']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($connection['en_category']): ?>
                                                        <span class="category-tag"><?php echo esc_html($connection['en_category']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="connection-arrow">⟷</div>
                                    
                                    <div class="language-section">
                                        <div class="connection-details">
                                            <span class="language-flag">🇮🇹</span>
                                            <div>
                                                <div class="page-title">
                                                    <a href="<?php echo esc_url(get_edit_post_link($connection['it_id'])); ?>" 
                                                       class="page-link" target="_blank">
                                                        <?php echo esc_html($connection['it_title']); ?>
                                                    </a>
                                                </div>
                                                <div class="page-meta">
                                                    ID: <?php echo $connection['it_id']; ?>
                                                    <?php if ($connection['it_brand']): ?>
                                                        <span class="brand-tag"><?php echo esc_html($connection['it_brand']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($connection['it_category']): ?>
                                                        <span class="category-tag"><?php echo esc_html($connection['it_category']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="disconnect-actions">
                                    <form method="post" style="display: inline;" class="single-disconnect-form">
                                        <?php wp_nonce_field('manual_page_disconnector_action', 'manual_page_disconnector_nonce'); ?>
                                        <input type="hidden" name="en_id" value="<?php echo $connection['en_id']; ?>">
                                        <input type="hidden" name="it_id" value="<?php echo $connection['it_id']; ?>">
                                        <input type="hidden" name="disconnect_single" value="1">
                                        <input type="hidden" name="debug_mode" value="1">
                                        <button type="submit" class="disconnect-btn" 
                                                onclick="return confirm('Are you sure you want to disconnect these pages?');">
                                            Disconnect
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        
        <script>
        jQuery(document).ready(function($) {
            const connections = <?php echo json_encode($connections); ?>;
            
            // Initialize
            populateFilters();
            updateCounts();
            
            function populateFilters() {
                const brands = new Set();
                const categories = new Set();
                
                connections.forEach(conn => {
                    if (conn.en_brand) brands.add(conn.en_brand);
                    if (conn.it_brand) brands.add(conn.it_brand);
                    if (conn.en_category) categories.add(conn.en_category);
                    if (conn.it_category) categories.add(conn.it_category);
                });
                
                let brandOptions = '<option value="">All Brands</option>';
                [...brands].sort().forEach(brand => {
                    brandOptions += `<option value="${brand}">${brand}</option>`;
                });
                $('#brand-filter').html(brandOptions);
                
                let categoryOptions = '<option value="">All Categories</option>';
                [...categories].sort().forEach(category => {
                    categoryOptions += `<option value="${category}">${category}</option>`;
                });
                $('#category-filter').html(categoryOptions);
            }
            
            function filterConnections() {
                const brandFilter = $('#brand-filter').val();
                const categoryFilter = $('#category-filter').val();
                const searchFilter = $('#search-filter').val().toLowerCase();
                
                let visibleCount = 0;
                
                $('.connection-item').each(function() {
                    const $item = $(this);
                    let visible = true;
                    
                    // Brand filter
                    if (brandFilter) {
                        const enBrand = $item.data('brand-en');
                        const itBrand = $item.data('brand-it');
                        if (enBrand !== brandFilter && itBrand !== brandFilter) {
                            visible = false;
                        }
                    }
                    
                    // Category filter
                    if (categoryFilter && visible) {
                        const enCategory = $item.data('category-en');
                        const itCategory = $item.data('category-it');
                        if (enCategory !== categoryFilter && itCategory !== categoryFilter) {
                            visible = false;
                        }
                    }
                    
                    // Search filter
                    if (searchFilter && visible) {
                        const enTitle = $item.data('title-en');
                        const itTitle = $item.data('title-it');
                        if (!enTitle.includes(searchFilter) && !itTitle.includes(searchFilter)) {
                            visible = false;
                        }
                    }
                    
                    if (visible) {
                        $item.show();
                        visibleCount++;
                    } else {
                        $item.hide();
                        // Uncheck hidden items
                        $item.find('.connection-select').prop('checked', false);
                    }
                });
                
                $('#visible-count').text(`${visibleCount} connections`);
                $('#filtered-count').text(`${visibleCount} shown`);
                updateCounts();
            }
            
            function updateCounts() {
                const selectedCount = $('.connection-select:checked').length;
                $('#selected-count').text(`${selectedCount} selected`);
                $('#bulk-disconnect-btn').prop('disabled', selectedCount === 0);
            }
            
            // Event handlers
            $('#brand-filter, #category-filter, #search-filter').on('change keyup', filterConnections);
            
            $('#clear-filters').on('click', function() {
                $('#brand-filter, #category-filter').val('');
                $('#search-filter').val('');
                filterConnections();
            });
            
            $('#select-all').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('.connection-item:visible .connection-select').prop('checked', isChecked);
                updateCounts();
            });
            
            $(document).on('change', '.connection-select', function() {
                updateCounts();
                
                // Update select all checkbox
                const visibleCheckboxes = $('.connection-item:visible .connection-select');
                const checkedCheckboxes = $('.connection-item:visible .connection-select:checked');
                $('#select-all').prop('checked', visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedCheckboxes.length);
            });
            
            // Confirm bulk disconnect
            $('#bulk-disconnect-form').on('submit', function(e) {
                const selectedCount = $('.connection-select:checked').length;
                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('Please select at least one connection to disconnect.');
                    return false;
                }
                
                if (!confirm(`Are you sure you want to disconnect ${selectedCount} page pair(s)?`)) {
                    e.preventDefault();
                    return false;
                }
                
                $('#bulk-disconnect-btn').prop('disabled', true).text('Disconnecting...');
            });
            
            // Single disconnect forms
            $('.single-disconnect-form').on('submit', function() {
                $(this).find('.disconnect-btn').prop('disabled', true).text('Disconnecting...');
            });
        });
        </script>
    </div>
    <?php
}

// Get all page connections with detailed information
function get_all_connections($pages) {
    $connections = [];
    $processed_pairs = [];
    
    foreach ($pages as $page) {
        $translations = pll_get_post_translations($page->ID);
        
        if (is_array($translations) && count($translations) > 1) {
            $en_id = isset($translations['en']) ? $translations['en'] : null;
            $it_id = isset($translations['it']) ? $translations['it'] : null;
            
            if ($en_id && $it_id && $en_id != $it_id) {
                // Create a unique key to avoid duplicates
                $pair_key = min($en_id, $it_id) . '-' . max($en_id, $it_id);
                
                if (!in_array($pair_key, $processed_pairs)) {
                    $processed_pairs[] = $pair_key;
                    
                    $en_page = get_post($en_id);
                    $it_page = get_post($it_id);
                    
                    if ($en_page && $it_page) {
                        $connections[] = [
                            'en_id' => $en_id,
                            'it_id' => $it_id,
                            'en_title' => $en_page->post_title,
                            'it_title' => $it_page->post_title,
                            'en_brand' => disconnector_extract_brand($en_page->post_title),
                            'it_brand' => disconnector_extract_brand($it_page->post_title),
                            'en_category' => disconnector_categorize_page($en_page->post_title),
                            'it_category' => disconnector_categorize_page($it_page->post_title),
                            'en_url' => get_permalink($en_id),
                            'it_url' => get_permalink($it_id)
                        ];
                    }
                }
            }
        }
    }
    
    // Sort by English title
    usort($connections, function($a, $b) {
        return strcmp($a['en_title'], $b['en_title']);
    });
    
    return $connections;
}

// Disconnect a single pair of pages
function disconnect_single_page_pair($en_page_id, $it_page_id) {
    // Verify pages exist
    $en_page = get_post($en_page_id);
    $it_page = get_post($it_page_id);
    
    if (!$en_page || !$it_page) {
        return [
            'success' => false,
            'message' => sprintf('Error: One or both pages do not exist (EN: %d, IT: %d)', $en_page_id, $it_page_id)
        ];
    }
    
    // Get current translations for both pages
    $en_translations = pll_get_post_translations($en_page_id);
    $it_translations = pll_get_post_translations($it_page_id);
    
    // Verify they are actually connected
    if (!isset($en_translations['it']) || $en_translations['it'] != $it_page_id) {
        return [
            'success' => false,
            'message' => sprintf('Pages are not connected: %s and %s', $en_page->post_title, $it_page->post_title)
        ];
    }
    
    // Remove the connection by creating new translation arrays without the other language
    $new_en_translations = ['en' => $en_page_id];
    $new_it_translations = ['it' => $it_page_id];
    
    // Save the new translations (effectively disconnecting them)
    $en_result = pll_save_post_translations($new_en_translations);
    $it_result = pll_save_post_translations($new_it_translations);
    
    if ($en_result !== false && $it_result !== false) {
        return [
            'success' => true,
            'message' => sprintf('Successfully disconnected: %s and %s', $en_page->post_title, $it_page->post_title)
        ];
    } else {
        return [
            'success' => false,
            'message' => sprintf('Failed to disconnect: %s and %s', $en_page->post_title, $it_page->post_title)
        ];
    }
}

// Enhanced categorization function for disconnector
function disconnector_categorize_page($title) {
    $title_lower = strtolower($title);
    
    $categories = [
        'Washing Machines' => ['washing', 'lavatrice', 'lavatrici', 'washer'],
        'Dryers' => ['dryer', 'asciugatrice', 'asciugatrici', 'tumble'],
        'Dishwashers' => ['dishwasher', 'lavastoviglie', 'dish'],
        'Refrigerators' => ['refrigerator', 'fridge', 'frigorifero', 'frigo'],
        'Ovens' => ['oven', 'forno', 'forni', 'microwave'],
        'Cooktops' => ['cooktop', 'hob', 'piano cottura', 'gas'],
        'Hoods' => ['hood', 'range hood', 'cappa', 'extractor'],
        'Air Conditioners' => ['air conditioner', 'condizionatore', 'ac unit', 'clima'],
        'Water Heaters' => ['water heater', 'boiler', 'scaldabagno', 'caldaia']
    ];
    
    foreach ($categories as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($title_lower, $keyword) !== false) {
                return $category;
            }
        }
    }
    
    return 'Other';
}

// Enhanced brand extraction for disconnector
function disconnector_extract_brand($title) {
    $brands = [
        'Miele', 'Bosch', 'Siemens', 'Samsung', 'LG', 'Whirlpool', 
        'Electrolux', 'AEG', 'Indesit', 'Hotpoint', 'Ariston', 
        'Smeg', 'Candy', 'Beko', 'Zanussi', 'Hoover', 'Gorenje',
        'Liebherr', 'Neff', 'Gaggenau', 'Fisher & Paykel', 'GE',
        'Kenmore', 'Maytag', 'KitchenAid', 'Frigidaire', 'Haier'
    ];
    
    foreach ($brands as $brand) {
        if (stripos($title, $brand) !== false) {
            return $brand;
        }
    }
    
    return null;
}
?>