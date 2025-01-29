<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<title>SB Admin 2 - Blank</title>

	<!-- Custom fonts for this template-->
	<link
		href="<?php echo base_url('/'); ?>files/vendor/fontawesome-free/css/all.min.css"
		rel="stylesheet"
		type="text/css" />
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet" />

	<!-- Custom styles for this template-->
	<link href="<?php echo base_url('/'); ?>files/css/sb-admin-2.min.css" rel="stylesheet" />

	<!-- Custom styles for this page -->
	<link href="<?php echo base_url('/'); ?>files/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />

	<!-- Font Awesome -->
	<link
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
		rel="stylesheet" />
	<style>
		.timeline {
			display: flex;
			flex-direction: column;
			padding: 20px;
		}

		.timeline-item {
			display: flex;
			align-items: center;
			margin-bottom: 20px;
			position: relative;
		}

		.timeline-item .timeline-icon {
			width: 30px;
			height: 30px;
			background-color: #ddd;
			border-radius: 50%;
			display: flex;
			justify-content: center;
			align-items: center;
			margin-right: 20px;
			font-size: 18px;
			color: #fff;
		}

		.timeline-item .timeline-content {
			background-color: #f9f9f9;
			padding: 10px;
			border-radius: 8px;
			width: 100%;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.timeline-item.completed .timeline-icon {
			background-color: #28a745;
			/* Green for completed steps */
		}

		.timeline-item.completed .timeline-content {
			background-color: #eaf7e6;
			/* Lighter green background for completed steps */
		}

		.timeline-date {
			font-size: 12px;
			color: #888;
			margin-top: 5px;
		}

		.timeline-item:last-child {
			margin-bottom: 0;
		}
	</style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body id="page-top">
	<!-- Page Wrapper -->
	<div id="wrapper">