# Manual Page Disconnector - Enhanced

[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg)](https://wordpress.org/)
[![Polylang](https://img.shields.io/badge/Polylang-Compatible-green.svg)](https://polylang.pro/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

> **Visually disconnect pages between languages with advanced orphaned connection cleanup for Polylang**

A comprehensive WordPress plugin that provides an intuitive interface to view, manage, and clean up page translations in Polylang. Perfect companion to the [Manual Page Connector](https://github.com/Terrachad/polylang-connector).

## 🔗 **Companion Plugin**

This plugin works alongside the **[Manual Page Connector](https://github.com/Terrachad/polylang-connector)** to provide complete translation management:

- **🔗 Connector**: Connect pages between languages with AI suggestions
- **🔓 Disconnector**: View, disconnect, and clean up existing page translations

## ✨ **Key Features**

### 🎯 **Core Functionality**
- **Visual Connection Overview**: See all connected page pairs in a clean, organized interface
- **Individual Disconnection**: Remove specific translation links with a single click
- **Bulk Disconnection**: Select multiple connections and disconnect them all at once
- **Advanced Orphaned Cleanup**: Detect and clean broken translation references

### 🧹 **Orphaned Connection Management**
- **Automatic Detection**: Identifies translations pointing to deleted pages
- **Smart Cleanup**: Removes broken references from existing pages
- **Force Cleanup**: Backup method for stubborn orphaned connections
- **Detailed Reporting**: Shows exactly what was cleaned and why

### 🔍 **Smart Interface**
- **Side-by-side Display**: Clear visualization of English ⟷ Italian page pairs
- **Advanced Filtering**: Filter connections by brand, category, or search terms
- **Live Statistics**: Real-time counts of total, filtered, and selected connections
- **Direct Edit Links**: Click page titles to edit them directly in WordPress

### 🛡️ **Safety & Reliability**
- **Confirmation Dialogs**: Always asks before disconnecting pages
- **Detailed Feedback**: Shows exactly which pages were processed
- **Comprehensive Debug Mode**: Full logging for troubleshooting
- **Non-destructive**: Only removes translation links, never deletes pages

### 📊 **Organization Features**
- **Brand Recognition**: Automatically detects appliance brands (Miele, Bosch, Samsung, etc.)
- **Category Classification**: Groups pages by appliance type (Washing Machines, Dryers, etc.)
- **Connection Status**: Clear indicators of which pages are connected
- **Bulk Operations**: Select all visible results for mass operations

## 🆘 **Orphaned Connection Problem**

### **What Are Orphaned Connections?**
When you delete pages from WordPress, Polylang translation data can remain, creating "orphaned" connections:

```
English Page (ID: 12941) → Italian Page (ID: 12963) [DELETED]
```

### **Why This Causes Issues:**
- **Connector Plugin**: Shows pages as "connected" (reads translation data)
- **Disconnector Plugin**: Can't show them (filters out deleted pages)  
- **Data Inconsistency**: Plugins show different connection counts

### **Our Solution:**
- **🔍 Automatic Detection**: Finds orphaned connections automatically
- **🧹 Smart Cleanup**: Removes broken translation references
- **⚡ Force Cleanup**: Backup method for completely deleted pages
- **📊 Clear Reporting**: Shows what was cleaned and why

## 📋 **Requirements**

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Polylang**: Latest version (free or pro)
- **Active Theme**: Any WordPress theme

## 🚀 **Installation**

### Method 1: Manual Installation

1. **Download** the plugin files
2. **Create folder**: `/wp-content/plugins/page_disconnector/`
3. **Upload** `page_disconnector.php` to the folder
4. **Activate** the plugin in WordPress Admin → Plugins

### Method 2: Git Clone

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/Terrachad/polylang-disconnector.git page_disconnector
```

Then activate in WordPress admin.

## 📖 **Usage Guide**

### Getting Started

1. **Navigate** to `Tools → Manual Page Disconnector` in WordPress admin
2. **View** all existing page connections in the interface
3. **Check for orphaned connections** (shown in yellow warning box)

### Managing Orphaned Connections

#### When You See This Warning:
```
⚠️ Orphaned Translation Connections Detected
Found X translation connections pointing to deleted pages. These should be cleaned up.
```

#### Cleanup Options:

**🧹 Regular Cleanup (Recommended)**
- **Best for**: Most orphaned connection issues
- **How it works**: Scans all pages and removes broken translation references
- **Use when**: You see the orphaned connections warning

**⚡ Force Cleanup (Backup)**
- **Best for**: Stubborn cases where regular cleanup doesn't work
- **How it works**: Directly targets known problematic page IDs
- **Use when**: Regular cleanup shows "0 cleaned" despite detecting orphans

### Disconnecting Active Connections

#### Individual Disconnection
- Click the red **"Disconnect"** button next to any connection
- Confirm the action in the popup dialog
- Page translation link will be removed immediately

#### Bulk Disconnection
- Use **checkboxes** to select multiple connections
- Click **"Disconnect Selected"** button
- Confirm bulk action to remove all selected connections

### Filtering & Search

```
🔍 Filters Available:
├── Brand Filter (Miele, Bosch, Samsung, etc.)
├── Category Filter (Washing Machines, Dryers, etc.)
└── Text Search (search page titles)
```

## 🔧 **Technical Details**

### Core Functions

```php
// Get all existing connections with details
get_disconnector_all_connections($pages)

// Disconnect specific page pair
disconnector_disconnect_single_page_pair($en_id, $it_id)

// Detect orphaned connections
get_orphaned_connections($pages, &$debug_info)

// Clean up orphaned references (direct approach)
cleanup_orphaned_translation($page_id, $lang, &$debug_info)
```

### Orphaned Connection Detection

```php
// Scan existing pages for broken translation references
foreach ($pages as $page) {
    $translations = pll_get_post_translations($page_id);
    foreach ($translations as $lang => $translation_id) {
        $translated_page = get_post($translation_id);
        if (!$translated_page) {
            // Found orphaned reference - remove it
        }
    }
}
```

### Database Operations
- **Read-only scanning** of existing Polylang translation data
- **Safe disconnection** by updating translation arrays
- **Orphaned cleanup** removes broken references only
- **No direct database queries** - uses Polylang API exclusively

## 🏷️ **Supported Brands & Categories**

### Appliance Brands
- **Premium**: Miele, Bosch, Siemens, Gaggenau, Neff
- **Major**: Samsung, LG, Whirlpool, Electrolux, AEG
- **European**: Smeg, Candy, Beko, Zanussi, Hoover, Gorenje
- **Others**: Indesit, Hotpoint, Ariston, Liebherr, Haier

### Product Categories
- 🧺 Washing Machines & Dryers
- 🍽️ Dishwashers & Kitchen Appliances  
- ❄️ Refrigerators & Freezers
- 🔥 Ovens, Cooktops & Hoods
- 🌡️ Air Conditioners & Water Heaters

## 🆚 **Connector vs Disconnector**

| Feature | Connector | Disconnector |
|---------|-----------|-------------|
| **Purpose** | Create new connections | Remove existing connections |
| **Interface** | Side-by-side selection | Connected pairs list |
| **AI Features** | Smart suggestions | Orphaned detection |
| **Bulk Actions** | Connect multiple | Disconnect multiple |
| **Filtering** | Filter available pages | Filter connected pairs |
| **Cleanup** | - | Advanced orphaned cleanup |

## 🐛 **Troubleshooting**

### Common Issues & Solutions

**Problem**: Orphaned connections detected but cleanup shows "0 cleaned"
- **Cause**: Pages were completely deleted (not trashed)
- **Solution**: Use **⚡ Force Cleanup** button instead

**Problem**: Connector and Disconnector show different connection counts
- **Cause**: Orphaned translation data from deleted pages
- **Solution**: Run **🧹 Clean Up Orphaned Connections**

**Problem**: Function conflicts with connector plugin
- **Cause**: Both plugins loaded simultaneously
- **Solution**: All functions are prefixed - should work together

**Problem**: No connections shown
- **Cause**: No pages are actually connected via Polylang
- **Solution**: Use Manual Page Connector to create connections first

### Debug Mode

Enable detailed logging by adding `?debug=1` to the admin URL:

```
Tools → Manual Page Disconnector?debug=1
```

### Understanding Orphaned vs Trashed Pages

```php
// Trashed pages (still exist in database)
get_post(12345) // Returns post object with post_status = 'trash'

// Completely deleted pages (removed from database)  
get_post(12345) // Returns NULL - page doesn't exist
```

**Our plugin handles both cases** - the force cleanup is especially effective for completely deleted pages.

## 🔄 **Cleanup Process Explained**

### What Happens During Cleanup:

1. **Scan Phase**: Check all existing pages for translation data
2. **Detection Phase**: Identify translations pointing to non-existent pages
3. **Cleanup Phase**: Remove broken translation references
4. **Save Phase**: Update translation data with only valid references

### Before Cleanup:
```json
Page 12941 translations: {"en": 12941, "it": 12963}
Page 12963: NULL (deleted)
```

### After Cleanup:
```json
Page 12941 translations: {"en": 12941}
Page 12963: Still deleted, but no broken references remain
```

## 🤝 **Contributing**

1. **Fork** the repository
2. **Create** feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** changes (`git commit -m 'Add amazing feature'`)
4. **Push** to branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Development Setup

```bash
# Clone repository
git clone https://github.com/Terrachad/polylang-disconnector.git

# Install in WordPress
cp -r polylang-disconnector /path/to/wordpress/wp-content/plugins/page_disconnector

# Activate and test
```

### Testing Checklist

- [ ] Regular disconnection works
- [ ] Bulk disconnection works  
- [ ] Orphaned detection works
- [ ] Regular cleanup works
- [ ] Force cleanup works
- [ ] Filtering works
- [ ] Debug mode provides useful info

## 📄 **License**

This project is licensed under the **GPL v2 License** - see the [LICENSE](LICENSE) file for details.

## 🎯 **Roadmap**

### Planned Features
- **Multi-language support** (beyond EN/IT)
- **Scheduled cleanup** for orphaned connections
- **Export/Import** connection data
- **Integration** with other translation plugins
- **Advanced reporting** dashboard

### Recent Updates
- ✅ **v1.1.0**: Added advanced orphaned connection cleanup
- ✅ **v1.0.1**: Fixed function name conflicts with connector
- ✅ **v1.0.0**: Initial release with basic disconnect functionality

## 🙏 **Acknowledgments**

- **[Polylang](https://polylang.pro/)** - For the excellent multilingual framework
- **[WordPress](https://wordpress.org/)** - For the amazing platform

## 📞 **Support**

- **Issues**: [GitHub Issues](https://github.com/Terrachad/polylang-disconnector/issues)
- **Discussions**: [GitHub Discussions](https://github.com/Terrachad/polylang-disconnector/discussions)
- **Documentation**: This README

## 💡 **Pro Tips**

### Best Practices
1. **Run orphaned cleanup** regularly if you frequently delete pages
2. **Use force cleanup** when pages are completely deleted (not trashed)
3. **Enable debug mode** when troubleshooting issues
4. **Filter connections** to find specific pages quickly
5. **Use bulk operations** for efficiency with large datasets

### Performance Tips
- Plugin is optimized for **1000+ pages**
- **Filtering reduces** display load
- **Bulk operations** are more efficient than individual actions
- **Debug mode** adds logging overhead - disable in production

---

**Made with ❤️ for the WordPress community**

*Perfect companion to [Manual Page Connector](https://github.com/Terrachad/polylang-connector) for complete translation management*
