<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

//load classes. The rest is handled by theme
if( is_admin())
{
	
    function get_demo_content_install_page_list() {
        return array(
            'pages' => array(
                'home' => array('name' => 'Homepage', 'description' => '', 'role' => 'home', 'image' => 'goodgame-default.png', 'data' => 'YToxOntpOjA7YToxMDp7czo5OiJwb3N0X25hbWUiO3M6NjoiaG9tZS0yIjtzOjEyOiJwb3N0X2NvbnRlbnQiO3M6MTIyMzoiW3ZjX3JvdyBlbF9jbGFzcz0iZnVsbC13aWR0aCJdW3ZjX2NvbHVtbl1baG9tZV9zbGlkZXJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdW2hvbWVfc2xpZGVyX2l0ZW0gc2xpZGVyX3Bvc3RfaWQ9IiJdWy9ob21lX3NsaWRlcl1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd11bdmNfcm93XVt2Y19jb2x1bW4gd2lkdGg9IjIvMyJdW3Bvc3RfbGlzdF9jb21wYWN0X2l0ZW1zIHRpdGxlPSJXaGF0J3MgbmV3IiBjb3VudD0iNSJdW2dvb2RnYW1lX2Jhbm5lcl83MjggYmFubmVyPSJkZWZhdWx0Il1bcG9zdF9jYXRlZ29yeV9zbGlkZXIgY2F0ZWdvcmllcz0icmV2aWV3IiBzaG93X2ZpcnN0PSJyZXZpZXciIGNvdW50PSIxMiIgaW50ZXJ2YWw9IjAiXVt2Y19yb3dfaW5uZXIgZWxfY2xhc3M9IndwYl9jb2x1bW5zXzNfd3JhcHBlciJdW3ZjX2NvbHVtbl9pbm5lciB3aWR0aD0iMS8zIl1bcG9zdF9saXN0X3dpdGhfaGVhZGluZyB0aXRsZT0iUEMiIGNvdW50PSI0IiBwbGF0Zm9ybT0icGMiXVsvdmNfY29sdW1uX2lubmVyXVt2Y19jb2x1bW5faW5uZXIgd2lkdGg9IjEvMyJdW3Bvc3RfbGlzdF93aXRoX2hlYWRpbmcgdGl0bGU9Ilhib3ggT25lIiBjb3VudD0iNCIgcGxhdGZvcm09Inhib3giXVsvdmNfY29sdW1uX2lubmVyXVt2Y19jb2x1bW5faW5uZXIgd2lkdGg9IjEvMyJdW3Bvc3RfbGlzdF93aXRoX2hlYWRpbmcgdGl0bGU9IlBsYXlzdGF0aW9uIDQiIGNvdW50PSI0IiBwbGF0Zm9ybT0icHM0Il1bL3ZjX2NvbHVtbl9pbm5lcl1bL3ZjX3Jvd19pbm5lcl1bZ29vZGdhbWVfYmFubmVyXzcyOCBiYW5uZXI9ImRlZmF1bHQiXVtwb3N0X3NsaWRlcl9sYXJnZSBjb3VudD0iNiJdW3Bob3RvX2dhbGxlcmllcyBjb3VudD0iMyIgY29sdW1ucz0iMyJdW2V4Y2x1c2l2ZV9wb3N0IHBvc3RfaWQ9IiJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdmNfd2lkZ2V0X3NpZGViYXIgc2lkZWJhcl9pZD0iZGVmYXVsdF9zaWRlYmFyIl1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10iO3M6MTA6InBvc3RfdGl0bGUiO3M6NjoiSG9tZSAyIjtzOjExOiJwb3N0X3N0YXR1cyI7czo3OiJwdWJsaXNoIjtzOjk6InBvc3RfdHlwZSI7czo0OiJwYWdlIjtzOjEyOiJwb3N0X2V4Y2VycHQiO3M6MDoiIjtzOjk6InBvc3RfZGF0ZSI7czoxOToiMjAxNi0xMS0yOCAxNDoyMjo1OCI7czoxMzoicG9zdF9kYXRlX2dtdCI7czoxOToiMjAxNi0xMS0yOCAxNDoyMjo1OCI7czoxMzoicGFnZV90ZW1wbGF0ZSI7czoxNToicGFnZS1sYXlvdXQucGhwIjtzOjk6InBvc3RfbWV0YSI7YTo4OntzOjE3OiJfdmNfcG9zdF9zZXR0aW5ncyI7YToxOntpOjA7czozMDoiYToxOntzOjEwOiJ2Y19ncmlkX2lkIjthOjA6e319Ijt9czoxMDoiX2VkaXRfbG9jayI7YToxOntpOjA7czoxMjoiMTQ4MDM0Mjg3MjoxIjt9czoxMDoiX2VkaXRfbGFzdCI7YToxOntpOjA7czoxOiIxIjt9czoxNzoiX3dwX3BhZ2VfdGVtcGxhdGUiO2E6MTp7aTowO3M6MTU6InBhZ2UtbGF5b3V0LnBocCI7fXM6MTQ6InNsaWRlX3RlbXBsYXRlIjthOjE6e2k6MDtzOjc6ImRlZmF1bHQiO31zOjEwOiJzaG93X3NoYXJlIjthOjE6e2k6MDtzOjA6IiI7fXM6MTQ6ImN1c3RvbV9zaWRlYmFyIjthOjE6e2k6MDtzOjY6Imdsb2JhbCI7fXM6MTc6Il93cGJfdmNfanNfc3RhdHVzIjthOjE6e2k6MDtzOjU6ImZhbHNlIjt9fX19'),
                'blog' => array('name' => 'Blog', 'description' => '', 'role' => 'blog', 'image' => 'blog.png', 'data' => 'YToxOntpOjA7YToxMDp7czo5OiJwb3N0X25hbWUiO3M6NDoiYmxvZyI7czoxMjoicG9zdF9jb250ZW50IjtzOjIzMDoiVXNlIHRoaXMgc3RhdGljIFBhZ2UgdG8gdGVzdCB0aGUgVGhlbWUncyBoYW5kbGluZyBvZiB0aGUgQmxvZyBQb3N0cyBJbmRleCBwYWdlLiBJZiB0aGUgc2l0ZSBpcyBzZXQgdG8gZGlzcGxheSBhIHN0YXRpYyBQYWdlIG9uIHRoZSBGcm9udCBQYWdlLCBhbmQgdGhpcyBQYWdlIGlzIHNldCB0byBkaXNwbGF5IHRoZSBCbG9nIFBvc3RzIEluZGV4LCB0aGVuIHRoaXMgdGV4dCBzaG91bGQgbm90IGFwcGVhci4iO3M6MTA6InBvc3RfdGl0bGUiO3M6NDoiQmxvZyI7czoxMToicG9zdF9zdGF0dXMiO3M6NzoicHVibGlzaCI7czo5OiJwb3N0X3R5cGUiO3M6NDoicGFnZSI7czoxMjoicG9zdF9leGNlcnB0IjtzOjA6IiI7czo5OiJwb3N0X2RhdGUiO3M6MTk6IjIwMTEtMDUtMjAgMTg6NTE6NDMiO3M6MTM6InBvc3RfZGF0ZV9nbXQiO3M6MTk6IjIwMTEtMDUtMjEgMDE6NTE6NDMiO3M6MTM6InBhZ2VfdGVtcGxhdGUiO3M6NzoiZGVmYXVsdCI7czo5OiJwb3N0X21ldGEiO2E6Mzp7czoxNzoiX3ZjX3Bvc3Rfc2V0dGluZ3MiO2E6MTp7aTowO3M6MzA6ImE6MTp7czoxMDoidmNfZ3JpZF9pZCI7YTowOnt9fSI7fXM6MTc6Il93cF9wYWdlX3RlbXBsYXRlIjthOjE6e2k6MDtzOjc6ImRlZmF1bHQiO31zOjEwOiJfZWRpdF9sb2NrIjthOjE6e2k6MDtzOjEyOiIxNDgwMzQyODE1OjEiO319fX0='),
                'contact' => array('name' => 'Contact page', 'description' => '', 'role' => '', 'image' => 'contact.png', 'data' => 'YToxOntpOjA7YToxMDp7czo5OiJwb3N0X25hbWUiO3M6MTI6ImNvbnRhY3QtdXMtMiI7czoxMjoicG9zdF9jb250ZW50IjtzOjE5MjI6Ilt2Y19yb3ddW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdGl0bGVfYmxvY2sgdGl0bGU9IldyaXRlIHRvIHVzIl1bY29udGFjdC1mb3JtLTcgaWQ9IjQiXVsvdmNfY29sdW1uXVt2Y19jb2x1bW4gd2lkdGg9IjEvMyJdW3RpdGxlX2Jsb2NrIHRpdGxlPSJGaW5kIHVzIG9uIG1hcCJdW3ZjX2dtYXBzIGxpbms9IiNFLThfSlRORGFXWnlZVzFsSlRJd2MzSmpKVE5FSlRJeWFIUjBjSE1sTTBFbE1rWWxNa1ozZDNjdVoyOXZaMnhsTG1OdmJTVXlSbTFoY0hNbE1rWmxiV0psWkNVelJuQmlKVE5FSlRJeE1XMHhPQ1V5TVRGdE1USWxNakV4YlRNbE1qRXhaRFl6TURRdU9ESTVPVGcyTVRNeE1qY3hKVEl4TW1RdE1USXlMalEzTkRZNU5qZ3dNek13T1RJbE1qRXpaRE0zTGpnd016YzBOelV5TVRZd05EUXpKVEl4TW0wekpUSXhNV1l3SlRJeE1tWXdKVEl4TTJZd0pUSXhNMjB5SlRJeE1Xa3hNREkwSlRJeE1tazNOamdsTWpFMFpqRXpMakVsTWpFemJUTWxNakV4YlRJbE1qRXhjekI0T0RBNE5UZzJaVFl6TURJMk1UVmhNU1V5TlROQk1IZzRObUprTVRNd01qVXhOelUzWXpBd0pUSXhNbk5UZEc5eVpYa2xNa0pCZG1VbE1qVXlReVV5UWxOaGJpVXlRa1p5WVc1amFYTmpieVV5TlRKREpUSkNRMEVsTWtJNU5ERXlPU1V5TVRWbE1DVXlNVE50TWlVeU1URnpaVzRsTWpFeWMzVnpKVEl4TkhZeE5ETTFPREkyTkRNeU1EVXhKVEl5SlRJd2QybGtkR2dsTTBRbE1qSTJNREFsTWpJbE1qQm9aV2xuYUhRbE0wUWxNakkwTlRBbE1qSWxNakJtY21GdFpXSnZjbVJsY2lVelJDVXlNakFsTWpJbE1qQnpkSGxzWlNVelJDVXlNbUp2Y21SbGNpVXpRVEFsTWpJbE1qQmhiR3h2ZDJaMWJHeHpZM0psWlc0bE0wVWxNME1sTWtacFpuSmhiV1VsTTBVPSJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdGl0bGVfYmxvY2sgdGl0bGU9IkFib3V0IHVzIl1bdmNfcmF3X2h0bWxdSlRORGNDVXpSVlp2Y25SaGJITWxNakJqY205emN5MXdiR0YwWm05eWJTVXlNR1JsY0d4dmVTVXlNSEJzWVhSbWIzSnRjeVV5TUhacGNtRnNKVEl3ZFhObGNpMWpiMjUwY21saWRYUmxaQ1V5TUdKbGRHRXRkR1Z6ZENVeU1IUmhaMk5zYjNWa2N5VXlReVV5TUcxdmNuQm9KVEl3WkhKcGRtVWxNakIyYjNKMFlXeHpKVEl3ZEdGbkpUSXdZbkpoYm1RdUpUSXdVRzl6ZENVeU1ISmxkbTlzZFhScGIyNXBlbVVsTWpCa2NtbDJaU1V5UXlVeU1ISmxZMjl1ZEdWNGRIVmhiR2w2WlNVeU1HSmxjM1F0YjJZdFluSmxaV1FsTWtNbE1qQlNUMGtsTWpCaGNtTm9hWFJsWTNRbE1qQmpkV3gwYVhaaGRHVWxNakIyYVhKMGRXRnNKVEl3WkdGMFlTMWtjbWwyWlc0bE1qQjFibXhsWVhOb0pUTkNKVEl3UTJ4MVpYUnlZV2x1SlRKREpUSXdaVzUwWlhKd2NtbHpaU1V6UWlVeU1HVXRaVzVoWW14bEpUSkRKVEl3ZDJWaWMyVnlkbWxqWlhNbE1qRWxNakJGZVdWaVlXeHNjeVV5UXlVeU1DVXlNbVJwYzNSeWFXSjFkR1ZrSlRJd2QyaHBkR1ZpYjJGeVpDVXlNR1Z1WkMxMGJ5MWxibVFsTWpCdFlYSnJaWFJ6SlRJd1ltVnpkQzF2WmkxaWNtVmxaQzRsTTBNbE1rWndKVE5GSlRCQkpUTkRjQ1V6UlVsdWRHVnlZV04wYVhabEpUSXdkR0ZuSlRJd2NtVjJiMngxZEdsdmJtbDZaU1V5TUcxbGRISnBZM01sTWpCd2NtOWhZM1JwZG1VbE1qQmxibUZpYkdVbE1qQmliRzluYjNOd2FHVnlaWE1sTWpCcGJtTmxiblJwZG1sNlpTVXlNRzl1WlMxMGJ5MXZibVVsTWtNbE1qQmtaWEJzYjNrbE1qQmthWE5wYm5SbGNtMWxaR2xoZEdVbE0wWWxNakJPWlhSM2IzSnJjeVV5TUhSMWNtNHRhMlY1SlRJd1lXUmtaV3hwZG1WeWVTVXlNSEJsWlhJdGRHOHRjR1ZsY2lVeU1HVXRZblZ6YVc1bGMzTWxNakJpWVdOckxXVnVaQ1V5TUdScGMzUnlhV0oxZEdWa0pUSXdaUzFsYm1GaWJHVWxNakJ6ZEhKbFlXMXNhVzVsTGlVelF5VXlSbkFsTTBVPVsvdmNfcmF3X2h0bWxdWy92Y19jb2x1bW5dWy92Y19yb3ddIjtzOjEwOiJwb3N0X3RpdGxlIjtzOjEwOiJDb250YWN0IHVzIjtzOjExOiJwb3N0X3N0YXR1cyI7czo3OiJwdWJsaXNoIjtzOjk6InBvc3RfdHlwZSI7czo0OiJwYWdlIjtzOjEyOiJwb3N0X2V4Y2VycHQiO3M6MDoiIjtzOjk6InBvc3RfZGF0ZSI7czoxOToiMjAxNi0xMS0yOCAxMzo1Njo1MCI7czoxMzoicG9zdF9kYXRlX2dtdCI7czoxOToiMjAxNi0xMS0yOCAxMzo1Njo1MCI7czoxMzoicGFnZV90ZW1wbGF0ZSI7czo3OiJkZWZhdWx0IjtzOjk6InBvc3RfbWV0YSI7YTo4OntzOjE3OiJfdmNfcG9zdF9zZXR0aW5ncyI7YToxOntpOjA7czozMDoiYToxOntzOjEwOiJ2Y19ncmlkX2lkIjthOjA6e319Ijt9czoxMDoiX2VkaXRfbG9jayI7YToxOntpOjA7czoxMjoiMTQ4MDM0MjU3MzoxIjt9czoxMDoiX2VkaXRfbGFzdCI7YToxOntpOjA7czoxOiIxIjt9czoxNzoiX3dwX3BhZ2VfdGVtcGxhdGUiO2E6MTp7aTowO3M6NzoiZGVmYXVsdCI7fXM6MTQ6InNsaWRlX3RlbXBsYXRlIjthOjE6e2k6MDtzOjc6ImRlZmF1bHQiO31zOjEwOiJzaG93X3NoYXJlIjthOjE6e2k6MDtzOjA6IiI7fXM6MTQ6ImN1c3RvbV9zaWRlYmFyIjthOjE6e2k6MDtzOjY6Imdsb2JhbCI7fXM6MTc6Il93cGJfdmNfanNfc3RhdHVzIjthOjE6e2k6MDtzOjQ6InRydWUiO319fX0='),
            )
        );
    }
    
    function get_demo_content_install_page_list_desc() {
        return array(
            'pages' => 'Pages',
        );
    }
    
    
    function get_demo_content_demo_list() {
        return array(
            'default',
        );
    }
    
    function get_demo_content_demo_desc() {
        return array(
            'default' => array('name' => 'GoodGame', 'image' => 'goodgame-default.png'),
        );
    }
    

	if(!class_exists('GoodGame_Demo_Export'))
	{
		require_once('demoExport.class.php');
	}
	if(!class_exists('GoodGame_Demo_Import'))
	{
		require_once('demoImport.class.php');
	}
}