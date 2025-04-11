<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index()
    {
        try {
            $blogs = Blog::with('user:id,name')->get()->map(function ($blog) {
                $blog->author = $blog->user ? $blog->user->name : '';
                unset($blog->user);
                return $blog;
            });

            return response()->json([
                'status' => true,
                'data' => $blogs,
            ]);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }

    public function show($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $blog,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ], 404);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:' . implode(',', [Blog::STATUS_DRAFT, Blog::STATUS_PUBLISHED]),
            ]);

            $blog = Blog::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'status' => $validated['status'],
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'status' => true,
                'data' => $blog,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'status' => 'nullable|in:' . implode(',', [Blog::STATUS_DRAFT, Blog::STATUS_PUBLISHED]),
            ]);

            $blog->update(array_filter($validated));

            return response()->json([
                'status' => true,
                'data' => $blog,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            return response()->json([
                'status' => true,
                'data' => null,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found',
            ], 404);
        } catch (\Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }

    public function changeStatus(Request $request, $id)
    {
    try {
        $blog = Blog::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [Blog::STATUS_DRAFT, Blog::STATUS_PUBLISHED]),
        ]);

        $blog->update(['status' => $validated['status']]);

        return response()->json([
            'status' => true,
            'data' => $blog,
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Blog not found',
        ], 404);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], 422);
    } catch (\Exception $e) {
        return $this->handleUnexpectedException($e);
    }
}
    private function handleUnexpectedException(\Exception $e)
    {
        // Optionally log here
        \Log::error($e);

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong. Please try again later.',
        ], 500);
    }
}
