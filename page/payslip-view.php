<!-- My Payslip Modal -->
<div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payslipModalLabel">Payslip Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Company and Payroll Info -->
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h6 class="fw-bold">Nordlich Pharma Inc.</h6>
                        <p class="text-muted mb-0">Main Branch</p>
                    </div>
                    <div>
                        <p class="mb-0"><span class="fw-bold">Pay Period:</span> <input type="month" class="form-control form-control-sm d-inline-block" style="width: 150px;" value="2025-08"></p>
                    </div>
                </div>
                <!-- Employee Info -->
                <table class="table table-bordered table-sm mb-4">
                    <tbody>
                        <tr>
                            <th class="bg-light">Employee</th>
                            <td>Juan Dela Cruz</td>
                            <th class="bg-light">Work Period</th>
                            <td>1st Half (15 days)</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Position</th>
                            <td>Accountant</td>
                            <th class="bg-light">Pay Date</th>
                            <td>Aug 15, 2025</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Employee ID</th>
                            <td>EMP-001</td>
                            <th class="bg-light">Worked Days</th>
                            <td>12 / 12</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Earnings and Deductions -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Earnings</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="text-end">₱ 25,000.00</td>
                                </tr>
                                <tr>
                                    <td>Overtime</td>
                                    <td class="text-end">₱ 1,200.00</td>
                                </tr>
                                <tr>
                                    <td>Allowance</td>
                                    <td class="text-end">₱ 800.00</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>Total Earnings</td>
                                    <td class="text-end">₱ 27,000.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger">Deductions</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>SSS</td>
                                    <td class="text-end">₱ 600.00</td>
                                </tr>
                                <tr>
                                    <td>PhilHealth</td>
                                    <td class="text-end">₱ 400.00</td>
                                </tr>
                                <tr>
                                    <td>Pag-IBIG</td>
                                    <td class="text-end">₱ 200.00</td>
                                </tr>
                                <tr>
                                    <td>Withholding Tax</td>
                                    <td class="text-end">₱ 1,000.00</td>
                                </tr>
                                <tr>
                                    <td>Other Deductions</td>
                                    <td class="text-end">₱ 250.00</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>Total Deductions</td>
                                    <td class="text-end">₱ 2,450.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <!-- Net Pay -->
                <div class="d-flex justify-content-end">
                    <div class="text-end bg-light p-2 rounded">
                        <h5 class="fw-bold mb-0">Net Pay: ₱ 24,550.00</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i>Download PDF</button>
            </div>
        </div>
    </div>
</div>