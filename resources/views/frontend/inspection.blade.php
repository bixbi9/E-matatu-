<!DOCTYPE html>
<html>
<head>
    <title>Inspection Form</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <style>
* {
  box-sizing: border-box;
}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
}

input[type=submit]:hover {
  background-color: #45a049;
}

input[type=exportpdf]:hover {
  background-color: #45a049;
}
.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

/* Clear floats after the columns */
.row::after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .col-25, .col-75, input[type=submit] {
    width: 100%;
    margin-top: 0;
  }
}
</style>
</head>
<body>
    <h2>Inspection Form</h2>
    <form action="/submit-inspection" method="POST">
        <!-- Inspection ID -->
        <div>
            <label for="inspection_id">Inspection ID:</label>
            <input type="number" id="inspection_id" name="inspection_id" required>
        </div>

        <!-- Vehicle ID -->
        <div>
            <label for="vehicle_id">Vehicle ID:</label>
            <input type="number" id="vehicle_id" name="vehicle_id">
        </div>

        <!-- Inspector Name -->
        <div>
            <label for="inspector_name">Inspector Name:</label>
            <input type="text" id="inspector_name" name="inspector_name" maxlength="50">
        </div>

        <!-- Result -->
        <div>
            <label for="result">Result:</label>
            <input type="text" id="result" name="result" maxlength="20">
        </div>

        <!-- Comments -->
        <div>
            <label for="comments">Comments:</label>
            <textarea id="comments" name="comments"></textarea>
        </div>

        <!-- Rating -->
        <div>
            <label for="rating">Rating:</label>
            <input type="text" id="rating" name="rating" maxlength="50">
        </div>

        <!-- Status -->
        <div>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" maxlength="50">
        </div>

        <!-- Inspection Date -->
        <div>
            <label for="inspection_date">Inspection Date:</label>
            <input type="date" id="inspection_date" name="inspection_date">
        </div>

        <!-- Evaluation Form -->
        <div>
            <label for="evaluation_form">Evaluation Form:</label>
            <textarea id="evaluation_form" name="evaluation_form"></textarea>
        </div>

        <!-- Maintenance Type -->
        <div>
            <label for="maintenance_type">Maintenance Type:</label>
            <input type="text" id="maintenance_type" name="maintenance_type" maxlength="50">
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit">Submit</button>
        </div>

        <div>
            <button type="button" onclick="exportPDF()">Export as PDF</button>
        </div>
    </form>

    <script>
        function exportPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Get the form values
            const inspectionId = document.getElementById('inspection_id').value;
            const vehicleId = document.getElementById('vehicle_id').value;
            const inspectorName = document.getElementById('inspector_name').value;
            // Get other form values similarly...

            // Add content to the PDF
            doc.text('Inspection Form', 10, 10);
            doc.text(`Inspection ID: ${inspectionId}`, 10, 20);
            doc.text(`Vehicle ID: ${vehicleId}`, 10, 30);
            doc.text(`Inspector Name: ${inspectorName}`, 10, 40);
            // Add other form values similarly...

            // Save the PDF
            doc.save('inspection.pdf');
        }
    </script>

</body>
</html>
