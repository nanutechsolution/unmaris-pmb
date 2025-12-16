<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa Validasi
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus tanggal setelah :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus tanggal sebelum :date.',
    'between'              => [
        'numeric' => ':attribute harus antara :min dan :max.',
        'file'    => ':attribute harus antara :min dan :max kilobytes.',
        'string'  => ':attribute harus antara :min dan :max karakter.',
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'              => ':attribute harus berupa true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus :digits digit.',
    'digits_between'       => ':attribute harus antara :min dan :max digit.',
    'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => ':attribute memiliki nilai duplikat.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu dari: :values.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa file.',
    'filled'               => ':attribute wajib diisi.',
    'gt'                   => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file'    => ':attribute harus lebih besar dari :value kilobytes.',
        'string'  => ':attribute harus lebih besar dari :value karakter.',
        'array'   => ':attribute harus memiliki lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => ':attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'string'  => ':attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus memiliki :value item atau lebih.',
    ],
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada di :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'lt'                   => [
        'numeric' => ':attribute harus kurang dari :value.',
        'file'    => ':attribute harus kurang dari :value kilobytes.',
        'string'  => ':attribute harus kurang dari :value karakter.',
        'array'   => ':attribute harus memiliki kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'file'    => ':attribute harus kurang dari atau sama dengan :value kilobytes.',
        'string'  => ':attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file'    => ':attribute tidak boleh lebih dari :max kilobytes.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes'                => ':attribute harus berupa file bertipe: :values.',
    'mimetypes'            => ':attribute harus berupa file bertipe: :values.',
    'min'                  => [
        'numeric' => ':attribute harus minimal :min.',
        'file'    => ':attribute harus minimal :min kilobytes.',
        'string'  => ':attribute harus minimal :min karakter.',
        'array'   => ':attribute harus memiliki minimal :min item.',
    ],
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => 'Kata sandi salah.',
    'present'              => ':attribute wajib ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_if'          => ':attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => ':attribute wajib diisi kecuali :other berada dalam :values.',
    'required_with'        => ':attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => ':attribute wajib diisi bila terdapat :values.',
    'required_without'     => ':attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => ':attribute wajib diisi bila tidak terdapat satupun :values.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => ':attribute harus berukuran :size kilobytes.',
        'string'  => ':attribute harus berukuran :size karakter.',
        'array'   => ':attribute harus mengandung :size item.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu dari: :values.',
    'string'               => ':attribute harus berupa string.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah terdaftar.',
    'uploaded'             => ':attribute gagal diunggah.',
    'url'                  => 'Format :attribute tidak valid.',
    'uuid'                 => ':attribute harus berupa UUID yang valid.',
    'required_if' => ':attribute wajib diisi bila :other adalah :value.',
    /*
    |--------------------------------------------------------------------------
    | Kustomisasi Nama Atribut
    |--------------------------------------------------------------------------
    |
    | Baris berikut digunakan untuk menukar placeholder atribut dengan sesuatu
    | yang lebih mudah dibaca seperti "Alamat E-Mail" daripada "email".
    |
    */

    'attributes' => [
        'name'                  => 'Nama Lengkap',
        'email'                 => 'Alamat Email',
        'password'              => 'Kata Sandi',
        'nomor_hp'              => 'Nomor HP/WhatsApp',

        // Data Diri Pendaftar
        'nisn'                  => 'NISN',
        'nik'                   => 'NIK',
        'tempat_lahir'          => 'Tempat Lahir',
        'tgl_lahir'             => 'Tanggal Lahir',
        'jenis_kelamin'         => 'Jenis Kelamin',
        'alamat'                => 'Alamat Lengkap',
        'agama'                 => 'Agama',

        // Sekolah & Ortu
        'asal_sekolah'          => 'Asal Sekolah',
        'tahun_lulus'           => 'Tahun Lulus',
        'nama_ayah'             => 'Nama Ayah',
        'pekerjaan_ayah'        => 'Pekerjaan Ayah',
        'nama_ibu'              => 'Nama Ibu',
        'pekerjaan_ibu'         => 'Pekerjaan Ibu',

        // Prodi
        'pilihan_prodi_1'       => 'Pilihan Prodi 1',
        'pilihan_prodi_2'       => 'Pilihan Prodi 2',
    ],
];
