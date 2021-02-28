$filePath='../uploads/1003-1.png';
if (file_exists($filePath)) 
{	    
    unlink($filePath);
    echo json_encode("Deleted");
}
else {
    echo json_encode("Not Deleted");
}