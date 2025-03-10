# Localizations
## In this starter you kan use HasLocalization trait in your model 

---
### **1. Apply the Trait in Your Model**
Example for an `Products` model (`ProductsModel`):

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bhry98\LaravelStarterKit\Traits\HasLocalization;

class ProductsModel extends Model
{
    use HasLocalization;

    protected $fillable = ['id', 'code'];
    protected $localizable = ['name']; // you don't need to add this columns in product table
}
```

---

### **2. Usage Example**

```php
$product = ProductsModel::find(50);
// or
$product = ProductsModel::create(['code'=>"pr-50"]);

// Add localized values
$enum->name = "Age";
$enum->setLocalized('name', 'العمر', 'ar');

// Retrieve localized values
App::setLocale('ar');
echo $enum->name; // العمر

App::setLocale('en');
echo $enum->name; // Age

// Delete localized value
$enum->deleteLocalized('name', 'ar');

// Force delete localized value
$enum->forceDeleteLocalized('name', 'ar');
```

---

### **Why This Works Well**
✅ Automatically detects and retrieves localized values  
✅ Works dynamically for any model  
✅ Allows for easy modifications and scalability
---