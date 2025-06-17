<!-- In your navigation, replace the user dropdown with: -->
<div class="flex items-center space-x-3">
    <x-user-avatar :user="Auth::user()" size="sm" />
    <span class="text-gray-700">{{ Auth::user()->name }}</span>
    <!-- Your dropdown menu here -->
</div>
