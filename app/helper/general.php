<?php
    use App\Models\User;
    
	if ( ! function_exists('company_filter')) {
        function company_filter($val=null) {
            
            $data = array(
                'customername' => __('Customer Name'),
                'phonenumber' => __('Phone Number'),
                'companyname' => __('Company Name'),
                'serviceid' => __('Service Name'),
                'email' => __('Email'),
                 );
                if ($val == null) {
                        return $data;
                } else {
                    return $data[$val];
                }
                return $data;
        }
	}

	if ( ! function_exists('product_filter')) {
        function product_filter($val=null) {
            
            $data = array(
                'productname' => __('Product Name'),
                'quantity' => __('Quantity'),
                'sku' => __('SKU'),
                'price' => __('Price'),
                'category' => __('Category'),
                 );
                if ($val == null) {
                        return $data;
                } else {
                    return $data[$val];
                }
                return $data;
        }
	}

	if ( ! function_exists('service_filter')) {
        function service_filter($val=null) {
            
            $data = array(
                'servicename' => __('Service Name'),
                'frequency' => __('Frequency'),
                'time' => __('Time'),
                'price' => __('Price')
                 );
                if ($val == null) {
                        return $data;
                } else {
                    return $data[$val];
                }
                return $data;
        }
	}

	if ( ! function_exists('quote_filter')) {
        function quote_filter($val=null) {
            
            $data = array(
                'customername' => __('Customer Name'),
                'servicename' => __('Service Name'),
                'frequency' => __('Frequency'),
                'price' => __('Price')
                 );
                if ($val == null) {
                        return $data;
                } else {
                    return $data[$val];
                }
                return $data;
        }
	}

    if (!function_exists('custom_fileupload')) 
    {
        function custom_fileupload($new_file, $path, $old_file_name = null) 
        {
            if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
            }
            if (isset($old_file_name) && $old_file_name != "" && file_exists(public_path() . '/' . $path . '/'. $old_file_name)) {
                unlink(public_path() . '/' . $path . '/' . $old_file_name);
            }
            $input['imagename'] = uniqid() . time() . '.' . $new_file->getClientOriginalExtension();
            $destinationPath = public_path($path);
            $new_file->move($destinationPath, $input['imagename']);

            $imageToResize = Image::make($destinationPath . '/' . $input['imagename']);
            $mainImgWidth = "300";
            $mainImgHeight = "200";
            $imageToResize->resize($mainImgWidth,$mainImgHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
            })->save($destinationPath . '/' . $input['imagename']);

            return $input['imagename'];
        }
    }

    if (!function_exists('custom_fileupload1')) 
    {
        function custom_fileupload1($new_file, $path, $thumbnailpath, $old_file_name = null) 
        {
            if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
            }
            if (isset($old_file_name) && $old_file_name != "" && file_exists(public_path() . '/' . $path . '/'. $old_file_name)) {
                unlink(public_path() . '/' . $path . '/' . $old_file_name);
            }

            if (!file_exists(public_path($thumbnailpath))) {
                    mkdir(public_path($thumbnailpath), 0777, true);
            }
            if (isset($old_file_name) && $old_file_name != "" && file_exists(public_path() . '/' . $thumbnailpath . '/'. $old_file_name)) {
                unlink(public_path() . '/' . $thumbnailpath . '/' . $old_file_name);
            }

            $input['imagename'] = uniqid() . time() . '.' . $new_file->getClientOriginalExtension();
            $destinationPath = public_path($path);
            $destinationPath1 = public_path($thumbnailpath);
            $new_file->move($destinationPath, $input['imagename']);

            $imageToResize = Image::make($destinationPath . '/' . $input['imagename']);
            $mainImgWidth = "300";
            $mainImgHeight = "200";
            $imageToResize->resize($mainImgWidth,$mainImgHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
            })->save($destinationPath . '/' . $input['imagename'])->resize('52','52', function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
            })->save($destinationPath1 . '/' . $input['imagename']);

            return $input['imagename'];
        }
    }

    if (!function_exists('custom_userinfo')) 
    {
        function custom_userinfo($authid) 
        {
            $unerinfo = User::select('googleplace')->where('role','superadmin')->first();
            
            return $unerinfo->googleplace;
        }
    }
?>