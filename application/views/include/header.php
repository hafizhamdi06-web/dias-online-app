<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title; ?></title>
    <meta name="author" content="Hafiz Hamdi">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSS Vendor -->
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/dist/css/adminlte.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/plugins//icheck-bootstrap/icheck-bootstrap.min.css'); ?>">    
  	<link rel="stylesheet" href="<?= base_url('/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">
  	<link rel="stylesheet" href="<?= base_url('/assets/plugins/select2/select2.css'); ?>">
  	<link rel="stylesheet" href="<?= base_url('/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css'); ?>">  
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/datatables-responsive/css/responsive.bootstrap4.css'); ?>">            
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/datatables-select/css/select.bootstrap4.css'); ?>">    
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/toastr/toastr.css'); ?>"> 
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/sweetalert2/sweetalert2.css'); ?>">    
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/datepicker/datepicker3.css'); ?>">       
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/bootstrap-switch/css/bootstrap-switch.css'); ?>">           
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="<?= app_url('/assets/fonts/css/ssPro.css'); ?>">
    <script src="<? echo app_url('assets/dist/js/modul/function.js'); ?>"></script>
    <script type="text/javascript">
      const base_url = "<?= base_url() ?>";      
      const copy = "<?= @$copy ?>";  
      const title = "<?= @$title2 ?>";            
      const form_id = "<?= @$id ?>";        
      const datapage = [[30],[30]];                      
    </script>
</head>
