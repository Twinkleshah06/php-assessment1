<?php

class Student {
    public $serial_no;
    public $first_name;
    public $last_name;
    public $contact_no;
    public $subjects;

    // Constructor to initialize student details
    public function __construct($serial_no, $first_name, $last_name, $contact_no, $subjects) {
        $this->serial_no = $serial_no;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->contact_no = $contact_no;
        $this->subjects = $subjects;
    }

    // Function to display student details
    public function display() {
        echo "\nSerial No: " . $this->serial_no . "\n";
        echo "Name: " . $this->first_name . " " . $this->last_name . "\n";
        echo "Contact: " . $this->contact_no . "\n";
        echo "Subjects and Marks:\n";
        foreach ($this->subjects as $subject => $details) {
            echo "  Subject: $subject, Marks: {$details['marks']}, Fees: {$details['fees']}\n";
        }
    }
}

class StudentManagementSystem {
    private $students = [];

    public function addStudent() {
        // Collecting student data
        $serial_no = readline("Enter Serial No : ");

        $first_name = readline("Enter First Name : ");

        $last_name = readline("Enter Last Name : ");

        $contact_no = readline("Enter Contact No : ");

        // Validate contact number to ensure it's numeric
        while (!is_numeric($contact_no)) {
            $contact_no = readline("Invalid contact number. Please enter a valid contact number : ");
        }

        $subjects = [];
        while (true) {
            $subject = readline("Enter Subject : ");

            $marks = readline("Enter Marks for $subject : ");

            $fees = readline("Enter Fees for $subject : ");

            // Store subject details in array
            $subjects[$subject] = ['marks' => $marks, 'fees' => $fees];

            $add_more = readline("Do you want to add another subject? (y/n) : ");
            if (strtolower($add_more) != 'y') {
                break;
            }
        }

        // Add student to the list
        $student = new Student($serial_no, $first_name, $last_name, $contact_no, $subjects);
        $this->students[] = $student;
        echo "Student added successfully!\n";
    }

    public function viewAllStudents() {
        if (empty($this->students)) {
            echo "No students available.\n";
            return;
        }
        foreach ($this->students as $student) {
            $student->display();
        }
    }

    public function viewSpecificStudent() {
        $serial_no = readline("Enter Student Serial No to search : ");

        $found = false;
        foreach ($this->students as $student) {
            if ($student->serial_no == $serial_no) {
                $student->display();
                $found = true;
                break;
            }
        }

        if (!$found) {
            echo "Student with Serial No $serial_no not found.\n";
        }
    }

    public function removeStudent() {
        $serial_no = readline("Enter Student Serial No to remove : ");

        // Confirm removal
        $confirmation = readline("Are you sure you want to remove Student with Serial No $serial_no? (Y/N) : ");

        if (strtolower($confirmation) == 'y') {
            $index = -1;
            foreach ($this->students as $key => $student) {
                if ($student->serial_no == $serial_no) {
                    $index = $key;
                    break;
                }
            }

            if ($index >= 0) {
                array_splice($this->students, $index, 1);
                echo "Student removed successfully.\n";
            } else {
                echo "Student not found.\n";
            }
        } else {
            echo "Removal canceled.\n";
        }
    }

    // Faculty can add marks to students
    public function addMarks() {
        $serial_no = readline("Enter Student Serial No to add marks : ");

        $found = false;
        foreach ($this->students as $student) {
            if ($student->serial_no == $serial_no) {
                $subject = readline("Enter Subject : ");

                $marks = readline("Enter Marks for $subject : ");

                $fees = readline("Enter Fees for $subject : ");

                // Add or update subject
                $student->subjects[$subject] = ['marks' => $marks, 'fees' => $fees];
                echo "Marks added successfully!\n";
                $found = true;
                break;
            }
        }

        if (!$found) {
            echo "Student with Serial No $serial_no not found.\n";
        }
    }
}

function displayMenu() {
    echo "\nMenu\n";
    echo "1. Counsellor\n";
    echo "2. Faculty\n";
    echo "3. Student\n";
    echo "Enter your choice: ";
}

function counsellorMenu($sms) {
    echo "\nCounsellor Menu:\n";
    echo "1. Add Student\n";
    echo "2. Remove Student\n";
    echo "3. View All Students\n";
    echo "4. View Specific Student\n";
    echo "Enter your choice: ";
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case 1:
            $sms->addStudent();
            break;
        case 2:
            $sms->removeStudent();
            break;
        case 3:
            $sms->viewAllStudents();
            break;
        case 4:
            $sms->viewSpecificStudent();
            break;
        default:
            echo "Invalid choice.\n";
            break;
    }
}

function facultyMenu($sms) {
    echo "\nFaculty Menu:\n";
    echo "1. Add Marks\n";
    echo "2. View All Students\n";
    $choice = readline("Enter your choice : ");

    switch ($choice) {
        case 1:
            $sms->addMarks();
            break;
        case 2:
            $sms->viewAllStudents();
            break;
        default:
            echo "Invalid choice.\n";
            break;
    }
}

function studentMenu() {
    echo "\nStudent Menu:\n";
    echo "1. View My Details\n";
    $choice = readline("Enter your choice : ");

    switch ($choice) {
        case 1:
            echo "You are a student. Viewing details is not implemented here.\n";
            break;
        default:
            echo "Invalid choice.\n";
            break;
    }
}

// Main program loop
$sms = new StudentManagementSystem();
while (true) {
    displayMenu();
    $role = trim(fgets(STDIN));

    switch ($role) {
        case 1:
            counsellorMenu($sms);
            break;
        case 2:
            facultyMenu($sms);
            break;
        case 3:
            studentMenu();
            break;
        default:
            echo "Invalid role. Exiting program.\n";
            exit();
    }

    // Check if user wants to continue
    $continue = readline("Do you want to perform another operation? (y/n) : ");
    if (strtolower($continue) != 'y') {
        break;
    }
}

?>
