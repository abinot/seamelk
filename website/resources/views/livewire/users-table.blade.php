<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>جدول کاربران با DataTables</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

</head>
<body>
    <table id="users-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام</th>
                <th>ایمیل</th>
                <th>تاریخ ساخت</th>
                <th>مقام</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ jdate($user->created_at)->format('Y/m/d') }}</td>
                <td>
                        @foreach ($user->roles as $role)
                            <span 
                                class="role-badge" 
                                data-name="{{ $role->name }}" 
                                data-created="{{ jdate($role->created_at)->format('Y/m/d H:i') }}"
                                data-updated="{{ jdate($role->updated_at)->format('Y/m/d H:i') }}"
                               >
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>

                 

            </tr>
            @endforeach
        </tbody>
        <!-- Modal ساده -->
<div id="roleModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
  <div style="background:gray; padding:20px; border-radius:12px; min-width:300px; text-align:center;">
      <h3 id="roleName" style="font-size:18px; margin-bottom:10px;"></h3>
      <p><strong>تاریخ ایجاد:</strong> <span id="roleCreated"></span></p>
      <p><strong>آخرین تغییر:</strong> <span id="roleUpdated"></span></p>
      <button id="closeModal" style="margin-top:12px; padding:6px 12px; border:none; border-radius:8px; color:white; cursor:pointer;">بستن</button>
  </div>
</div>
    </table>
    






<script>
    document.querySelectorAll('.role-badge').forEach(el => {
        el.addEventListener('click', () => {
            document.getElementById('roleName').textContent = el.dataset.name;
            document.getElementById('roleCreated').textContent = el.dataset.created;
            document.getElementById('roleUpdated').textContent = el.dataset.updated;
            document.getElementById('roleModal').style.display = 'flex';
        });
    });

    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('roleModal').style.display = 'none';
    });

    document.getElementById('roleModal').addEventListener('click', e => {
        if (e.target === e.currentTarget) e.currentTarget.style.display = 'none';
    });

        new DataTable('#users-table', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fa.json'
            }
        });
    </script>
</body>
</html>
