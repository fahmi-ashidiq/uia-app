<?php
// This file is included from index.php, which already started the session
// and exposes $user_id, $message, $messageCode. It also loaded config/database.php.
if (!isset($pdo)) {
    require __DIR__ . '/../../config/database.php';
}

$list_gender = ["M" => "Male", "F" => "Female"];

$firstname = $lastname = $gender = $birthday = $address = $email = $phone = "";
$is_active = true;
$img = "";
$province_code = $province_name = "";
$city_code = $city_name = "";
$district_code = $district_name = "";
$village_code = $village_name = "";

if (isset($user_id)) {
    $stmt = $pdo->prepare("SELECT * FROM tb_users WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();

    if ($profile) {
        $firstname = $profile['firstname'];
        $lastname  = $profile['lastname'];
        $gender    = $profile['gender'];
        $birthday  = $profile['birthday'];
        $address   = $profile['address'] ?? '';
        $email     = $profile['email'];
        $phone     = $profile['phone'];
        $is_active = (bool) $profile['is_active'];

        $img = (!empty($profile['image']) && file_exists(__DIR__ . '/../../asset/profile/' . $profile['image']))
            ? $profile['image']
            : '';

        $province_code = $profile['province_code'] ?? '';
        $province_name = $profile['province_name'] ?? '';
        $city_code     = $profile['city_code'] ?? '';
        $city_name     = $profile['city_name'] ?? '';
        $district_code = $profile['district_code'] ?? '';
        $district_name = $profile['district_name'] ?? '';
        $village_code  = $profile['village_code'] ?? '';
        $village_name  = $profile['village_name'] ?? '';
    }
}

$imgSrc = $img !== '' ? 'asset/profile/' . $img : 'https://placehold.co/200x200/png?text=No+Photo';
?>

<div class="head-title">
    <div class="left">
        <h1>Profile</h1>
        <ul class="breadcrumb">
            <li>
                <a href="#">Profile</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="index.php">Home</a>
            </li>
        </ul>
    </div>
</div>

<div class="form-style">
    <form id="basic-form" method="post" enctype="multipart/form-data" action="./module/account/update-profile.php">
        <div class="section">
            <span>1</span>&nbsp;&nbsp;My Account Profile
        </div>
        <div class="inner-wrap">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo htmlspecialchars($messageCode); ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <label class="img-profile">
                <div class="img-circle">
                    <img id="preview-img" src="<?php echo $imgSrc; ?>" alt="profile photo">
                    <i class='bx bx-pencil'></i>
                </div>
                <input type="file" name="photo" id="photo-input" accept="image/*" hidden>
            </label>

            <label>First Name
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required/>
            </label>
            <label>Last Name
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required/>
            </label>
            <label>Gender
                <select name="gender" style="width: 40%">
                    <?php foreach ($list_gender as $item => $name): ?>
                        <option value="<?= htmlspecialchars($item) ?>" <?= $item === $gender ? 'selected' : '' ?>>
                            <?= ucfirst($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Birthday
                <input type="date" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>" required/>
            </label>
            <label>Address
                <textarea name="address" rows="3" style="white-space: pre-wrap;"><?php echo htmlspecialchars(trim($address)) ?></textarea>
            </label>

            <!-- ===== Region cascading dropdowns (soal #3) ===== -->
            <label>Province
                <select id="province" name="province_code" data-selected="<?php echo htmlspecialchars($province_code); ?>">
                    <option value="">-- Select Province --</option>
                </select>
            </label>
            <label>City / Regency (Kab/Kota)
                <select id="city" name="city_code" data-selected="<?php echo htmlspecialchars($city_code); ?>" disabled>
                    <option value="">-- Select City / Regency --</option>
                </select>
            </label>
            <label>District (Kecamatan)
                <select id="district" name="district_code" data-selected="<?php echo htmlspecialchars($district_code); ?>" disabled>
                    <option value="">-- Select District --</option>
                </select>
            </label>
            <label>Village (Kelurahan/Desa)
                <select id="village" name="village_code" data-selected="<?php echo htmlspecialchars($village_code); ?>" disabled>
                    <option value="">-- Select Village --</option>
                </select>
            </label>

            <input type="hidden" name="province_name" id="province_name" value="<?php echo htmlspecialchars($province_name); ?>">
            <input type="hidden" name="city_name" id="city_name" value="<?php echo htmlspecialchars($city_name); ?>">
            <input type="hidden" name="district_name" id="district_name" value="<?php echo htmlspecialchars($district_name); ?>">
            <input type="hidden" name="village_name" id="village_name" value="<?php echo htmlspecialchars($village_name); ?>">
            <!-- ===== end region dropdowns ===== -->

            <label>Email
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required/>
            </label>
            <label>Phone Number
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required/>
            </label>
            <label>Is Active?
                <br/><br/>
                <input type="radio" name="status" value="true" <?= $is_active === true ? 'checked' : '' ?>/>True
                <input type="radio" name="status" value="false" <?= $is_active === false ? 'checked' : '' ?>/>False
            </label><br/>
            <div>
                <input type="button" name="cancel" value="Cancel" onclick="window.location.href='index.php'">
                <input type="Submit" name="submit" value="Save">
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const provinceEl = document.getElementById('province');
    const cityEl = document.getElementById('city');
    const districtEl = document.getElementById('district');
    const villageEl = document.getElementById('village');

    async function fetchRegion(url) {
        const res = await fetch(url);
        return res.json();
    }

    function fillSelect(selectEl, items, selectedValue, placeholder) {
        selectEl.innerHTML = '<option value="">' + placeholder + '</option>';
        items.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item.code;
            opt.textContent = item.name;
            if (selectedValue && item.code === selectedValue) opt.selected = true;
            selectEl.appendChild(opt);
        });
        selectEl.disabled = items.length === 0;
    }

    function syncHiddenNames() {
        document.getElementById('province_name').value = provinceEl.selectedOptions[0]?.textContent.trim() || '';
        document.getElementById('city_name').value = cityEl.selectedOptions[0]?.textContent.trim() || '';
        document.getElementById('district_name').value = districtEl.selectedOptions[0]?.textContent.trim() || '';
        document.getElementById('village_name').value = villageEl.selectedOptions[0]?.textContent.trim() || '';
    }

    async function loadChildren(parentCode, selectEl, selectedValue, placeholder) {
        if (!parentCode) {
            selectEl.innerHTML = '<option value="">' + placeholder + '</option>';
            selectEl.disabled = true;
            return;
        }
        const items = await fetchRegion('module/account/get-region.php?parent_code=' + encodeURIComponent(parentCode));
        fillSelect(selectEl, items, selectedValue, placeholder);
    }

    async function init() {
        const provinces = await fetchRegion('module/account/get-region.php?level=1');
        fillSelect(provinceEl, provinces, provinceEl.dataset.selected, '-- Select Province --');

        if (provinceEl.dataset.selected) {
            await loadChildren(provinceEl.dataset.selected, cityEl, cityEl.dataset.selected, '-- Select City / Regency --');
        }
        if (cityEl.dataset.selected) {
            await loadChildren(cityEl.dataset.selected, districtEl, districtEl.dataset.selected, '-- Select District --');
        }
        if (districtEl.dataset.selected) {
            await loadChildren(districtEl.dataset.selected, villageEl, villageEl.dataset.selected, '-- Select Village --');
        }
        syncHiddenNames();
    }

    provinceEl.addEventListener('change', async function () {
        await loadChildren(provinceEl.value, cityEl, '', '-- Select City / Regency --');
        districtEl.innerHTML = '<option value="">-- Select District --</option>';
        districtEl.disabled = true;
        villageEl.innerHTML = '<option value="">-- Select Village --</option>';
        villageEl.disabled = true;
        syncHiddenNames();
    });

    cityEl.addEventListener('change', async function () {
        await loadChildren(cityEl.value, districtEl, '', '-- Select District --');
        villageEl.innerHTML = '<option value="">-- Select Village --</option>';
        villageEl.disabled = true;
        syncHiddenNames();
    });

    districtEl.addEventListener('change', async function () {
        await loadChildren(districtEl.value, villageEl, '', '-- Select Village --');
        syncHiddenNames();
    });

    villageEl.addEventListener('change', syncHiddenNames);

    // Live photo preview before upload
    const photoInput = document.getElementById('photo-input');
    const previewImg = document.getElementById('preview-img');
    photoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            previewImg.src = URL.createObjectURL(this.files[0]);
        }
    });

    init();
})();
</script>
