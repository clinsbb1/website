<x-layouts.admin title="Change password">
  <h1 class="text-xl font-semibold text-stone-900">Change password</h1>

  <form method="POST" action="{{ route('admin.password.update') }}" class="mt-6 max-w-sm space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-stone-700">Current password</label>
      <input type="password" name="current_password" required class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
      @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-stone-700">New password</label>
      <input type="password" name="password" required class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
      @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-stone-700">Confirm new password</label>
      <input type="password" name="password_confirmation" required class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
    </div>

    <button type="submit" class="rounded-md bg-stone-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-stone-800">Update password</button>
  </form>
</x-layouts.admin>
