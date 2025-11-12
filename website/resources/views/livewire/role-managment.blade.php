<div>
<head>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>
    <table id="roles-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام نقش</th>
                <th>دسترسی‌ها</th>
                <th>تاریخ ایجاد</th>
                <th>فراوانی (تعداد کاربران)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    @foreach ($role->permissions as $permission)
                        <span class="permission-badge"
                              data-name="{{ $permission->name }}"
                              data-created="{{ jdate($permission->created_at)->format('Y/m/d H:i:s') }}"
                              data-updated="{{ jdate($permission->updated_at)->format('Y/m/d H:i:s') }}"
                              style="background:#10b981; color:white; border-radius:6px; padding:4px 8px; margin:2px; display:inline-block; font-size:0.85em; cursor:pointer;">
                            {{ $permission->name }}
                        </span>
                    @endforeach
                </td>
                <td>{{ jdate($role->created_at)->format('Y/m/d H:i:s') }}</td>
                <td>{{ $role->users()->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal ساده برای نمایش اطلاعات Permission -->
    <div id="permissionModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
         background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
      <div style="background:gray; padding:20px; border-radius:12px; min-width:300px; text-align:center;">
          <h3 id="permissionName" style="font-size:18px; margin-bottom:10px;"></h3>
          <p><strong>تاریخ ایجاد:</strong> <span id="permissionCreated"></span></p>
          <p><strong>آخرین تغییر:</strong> <span id="permissionUpdated"></span></p>
          <button id="closePermissionModal" style="margin-top:12px; padding:6px 12px; border:none; border-radius:8px; color:white; cursor:pointer;">بستن</button>
      </div>
    </div>

    <script>
        // کلیک روی Permission
        document.querySelectorAll('.permission-badge').forEach(el => {
            el.addEventListener('click', () => {
                document.getElementById('permissionName').textContent = el.dataset.name;
                document.getElementById('permissionCreated').textContent = el.dataset.created;
                document.getElementById('permissionUpdated').textContent = el.dataset.updated;
                document.getElementById('permissionModal').style.display = 'flex';
            });
        });

        document.getElementById('closePermissionModal').addEventListener('click', () => {
            document.getElementById('permissionModal').style.display = 'none';
        });

        document.getElementById('permissionModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) e.currentTarget.style.display = 'none';
        });

        // فعال‌سازی DataTable
        new DataTable('#roles-table', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fa.json'
            }
        });
    </script>

</div>