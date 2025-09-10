<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme position-relative">
    <livewire:admin.sidebar />

    <div class="position-absolute bottom-0 m-3">
        <button class="btn rounded-3 d-flex align-items-center gap-2 w-100" style="background: #000">
            <div>
                <img src="https://img.freepik.com/free-photo/portrait-man-laughing_23-2148859448.jpg?t=st=1727185449~exp=1727189049~hmac=174cae49c8da90bd3f8d1308bc89418301d16fb1276a0c399cc7b20f7f47af4b&w=740"
                    height="25px" width="25px" class="img-fluid rounded-circle" alt="">
            </div>
            <div>
                <h6 class="text-white m-0 text-start" style="font-size: 12px">{{ auth()->user()->name }}</h6>
                <p class="text-white m-0 text-truncate" style="font-size: 10px">{{ auth()->user()->email }}</p>
            </div>
        </button>
    </div>
</aside>



<div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background: #0EA7C1; color:white"
                    onclick="savePassword()">Update</button>
            </div>
        </div>
    </div>
</div>
