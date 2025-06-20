# Manual Page Disconnector - Enhanced

[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg)](https://wordpress.org/)
[![Polylang](https://img.shields.io/badge/Polylang-Compatible-green.svg)](https://polylang.pro/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

> **Visually disconnect pages between languages with detailed connection management for Polylang**

A comprehensive WordPress plugin that provides an intuitive interface to view and manage existing page translations in Polylang. Perfect companion to the [Manual Page Connector](https://github.com/Terrachad/polylang-connector).

## 🔗 **Companion Plugin**

This plugin works alongside the **[Manual Page Connector](https://github.com/Terrachad/polylang-connector)** to provide complete translation management:

- **🔗 Connector**: Connect pages between languages with AI suggestions
- **🔓 Disconnector**: View and disconnect existing page translations

## ✨ **Features**

### 🎯 **Core Functionality**
- **Visual Connection Overview**: See all connected page pairs in a clean, organized interface
- **Individual Disconnection**: Remove specific translation links with a single click
- **Bulk Disconnection**: Select multiple connections and disconnect them all at once
- **Real-time Filtering**: Filter by brand, category, or search by page title

### 🔍 **Smart Interface**
- **Side-by-side Display**: Clear visualization of English ⟷ Italian page pairs
- **Advanced Filtering**: Filter connections by brand, category, or search terms
- **Live Statistics**: Real-time counts of total, filtered, and selected connections
- **Direct Edit Links**: Click page titles to edit them directly in WordPress

### 🛡️ **Safety & Reliability**
- **Confirmation Dialogs**: Always asks before disconnecting pages
- **Detailed Feedback**: Shows exactly which pages were disconnected
- **Debug Mode**: Comprehensive debugging for troubleshooting
- **Selective Display**: Only shows actually connected pages

### 📊 **Organization Features**
- **Brand Recognition**: Automatically detects appliance brands (Miele, Bosch, Samsung, etc.)
- **Category Classification**: Groups pages by appliance type (Washing Machines, Dryers, etc.)
- **Connection Status**: Clear indicators of which pages are connected
- **Select All Visible**: Bulk select filtered results for mass operations

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
3. **Use filters** to find specific connections you want to manage

### Disconnecting Pages

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

### Interface Overview

```
📊 Statistics Bar
├── Total connections count
└── Currently filtered/visible count

🔧 Bulk Actions
├── Select All Visible checkbox
├── Disconnect Selected button
└── Selection counter

📝 Connection List
├── 🇺🇸 English Page ⟷ 🇮🇹 Italian Page
├── Brand and category tags
├── Direct edit links
└── Individual disconnect buttons
```

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

## 🔧 **Technical Details**

### Key Functions

```php
// Get all existing connections
get_disconnector_all_connections($pages)

// Disconnect specific page pair  
disconnector_disconnect_single_page_pair($en_id, $it_id)

// Brand and category detection
disconnector_extract_brand($title)
disconnector_categorize_page($title)
```

### Database Operations
- **Read-only scanning** of existing Polylang translation data
- **Safe disconnection** by updating translation arrays
- **No direct database queries** - uses Polylang API only

## 🆚 **Connector vs Disconnector**

| Feature | Connector | Disconnector |
|---------|-----------|-------------|
| **Purpose** | Create new connections | Remove existing connections |
| **Interface** | Side-by-side selection | Connected pairs list |
| **AI Features** | Smart suggestions | Visual organization |
| **Bulk Actions** | Connect multiple | Disconnect multiple |
| **Filtering** | Filter available pages | Filter connected pairs |

## 🐛 **Troubleshooting**

### Common Issues

**Problem**: No connections shown
- **Solution**: Ensure pages are actually connected via Polylang first

**Problem**: Function conflicts with connector
- **Solution**: Both plugins use prefixed functions - should work together

**Problem**: Disconnection not working
- **Solution**: Enable debug mode and check WordPress error logs

### Debug Mode

Add `?debug=1` to the admin URL or check debug information in the interface:

```
Tools → Manual Page Disconnector?debug=1
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

## 📄 **License**

This project is licensed under the **GPL v2 License** - see the [LICENSE](LICENSE) file for details.

## 🙏 **Acknowledgments**

- **[Polylang](https://polylang.pro/)** - For the excellent multilingual framework
- **[WordPress](https://wordpress.org/)** - For the amazing platform
- **Community** - For feedback and feature requests

## 📞 **Support**

- **Issues**: [GitHub Issues](https://github.com/Terrachad/polylang-disconnector/issues)
- **Discussions**: [GitHub Discussions](https://github.com/Terrachad/polylang-disconnector/discussions)
- **Email**: Create an issue for fastest response

---

**Made with ❤️ for the WordPress community**

*Perfect companion to [Manual Page Connector](https://github.com/Terrachad/polylang-connector) for complete translation management*
