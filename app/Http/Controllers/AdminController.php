<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UploadProductRequest;
use App\Services\ImageService;

class AdminController extends Controller
{
    public function viewCategory()
    {
        $categories = Category::all();

        return view('admin.category', compact('categories'));
    }

    public function addCategory(StoreCategoryRequest $request)
    {
        try {
            Category::create([
                'category_name' => $request->category,
            ]);

            toastr()->timeOut(10000)->closeButton()->addSuccess('Category Added Successfully');
        } catch (\Exception $e) {
            toastr()->timeOut(10000)->closeButton()->addError('Failed to add category. Please try again.');
        }

        return redirect()->back();
    }

    public function deleteCategory(int $id)
    {
        Category::destroy($id);

        toastr()->timeOut(10000)->closeButton()->addSuccess('Category Deleted Successfully');

        return redirect()->back();
    }

    public function editCategory(int $id)
    {
        $category = Category::findOrFail($id);

        return view('admin.edit_category', compact('category'));
    }

    public function updateCategory(Request $request, int $id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'category_name' => $request->category,
        ]);

        toastr()->timeOut(10000)->closeButton()->addSuccess('Category Updated Successfully');

        return redirect()->route('view_category');
    }

    public function addProduct()
    {
        $categories = Category::all();

        return view('admin.add_product', compact('categories'));
    }

    public function uploadProduct(UploadProductRequest $request, ImageService $imageService)
    {
        try {
            $imageName = $imageService->uploadImage($request->file('image'));

            Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->qty,
                'category' => $request->category,
                'image' => $imageName,
            ]);

            toastr()->timeOut(10000)->closeButton()->addSuccess('Product Added Successfully');
        } catch (\Exception $e) {
            toastr()->timeOut(10000)->closeButton()->addError('Failed to add product. Please try again.');
        }

        return redirect()->back();
    }

    public function viewProduct()
    {
        $products = Product::paginate(3);

        return view('admin.view_product', compact('products'));
    }

    public function deleteProduct(int $id)
    {
        $product = Product::findOrFail($id);

        $imagePath = public_path('products/' . $product->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $product->delete();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Deleted Successfully');

        return redirect()->back();
    }

    public function updateProduct(int $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.update_page', compact('product', 'categories'));
    }

    public function editProduct(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $imageName = $product->image;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('products'), $imageName);
        }

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'image' => $imageName,
        ]);

        toastr()->timeOut(10000)->closeButton()->addSuccess('Product Updated Successfully');

        return redirect()->route('view_product');
    }

    public function productSearch(Request $request)
    {
        $search = $request->search;

        $products = Product::where('title', 'LIKE', "%$search%")
            ->orWhere('category', 'LIKE', "%$search%")
            ->paginate(3);

        return view('admin.view_product', compact('products'));
    }

    public function viewOrder()
    {
        $orders = Order::all();

        return view('admin.order', compact('orders'));
    }

    public function onTheWay(int $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'On the way']);

        return redirect()->route('view_orders');
    }

    public function delivered(int $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Delivered']);

        return redirect()->route('view_orders');
    }

    public function printPdf(int $id)
    {
        $order = Order::findOrFail($id);
        $pdf = Pdf::loadView('admin.invoice', compact('order'));

        return $pdf->download('invoice.pdf');
    }
}
