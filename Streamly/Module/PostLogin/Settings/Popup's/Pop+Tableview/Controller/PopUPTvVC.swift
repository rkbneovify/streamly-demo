//
//  PopUPTvVC.swift
//  Streamly
//
//  Created by Rohit Singh on 6/5/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import Foundation

class PopUPTvVC: UIViewController {

    // MARK: - Outlets
    @IBOutlet weak var tableview:UITableView!
    @IBOutlet weak var backView:UIView!
    @IBOutlet weak var okButton:UIButton!

    // MARK: - Properties
    var profileItemArr:[(heading:String,data:String)]?
    var isProfile:Bool?
    {
        didSet{
            if isProfile ?? false {
                profileItemArr  = [("Username:", "\(String(describing: Utility.retriveStringObject(key: Constant.UserDefault.userName)))"),("Email:", "\(String(describing: Utility.retriveStringObject(key: Constant.UserDefault.email)))"),("Version:","\(GEVersionMonitor.shared.formattedVersion)"),("Build Date:","\(String(describing: Utility.retriveStringObject(key: Constant.UserDefault.createdAt)))")]
            }
        }
    }
    var deviceList:[RegistredDevice]? = []
    {
        didSet
        {
            self.tableview.reloadData()
        }
    }
    lazy var viewModel: RegistredDeviceViewModel = {
        return RegistredDeviceViewModel()
    }()

    override var preferredFocusedView: UIView? {
        get {
            return self.okButton
        }
    }

    // MARK: - LifeCycle Methods
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        self.initialSetup()
    }

    override func viewWillLayoutSubviews() {
        
        self.backView.backgroundColor = UIColor.gray.withAlphaComponent(0.9)
        self.okButton.setBtnProperties(self.okButton)

    }

    // MARK: - Action method
    @IBAction func okAction(_ sender:UIButton)
    {
        self.dismiss(animated: true, completion: nil)

    }

    // MARK: - Helpers

    private func initialSetup()
    {
        if !(isProfile ?? true)
        {
            self.showSpinner(onView: self.view)
            self.makeApiCall()
            self.initVM()
        }
       // self.tableview.delegate =  self
        self.tableview.dataSource  = self
        
    }
    
    func makeApiCall(){
        viewModel.delegate = self
        viewModel.getRegistredDeviceData()

    }
    
    private func initVM() {
        
        viewModel.registredDevice = { contactModel in
            self.deviceList = contactModel.data
            debugPrint("")
            self.removeSpinner()

            
        }
        
    }
    
    
}

extension PopUPTvVC:UITableViewDataSource
{
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {

        if ((profileItemArr?.count == 0 || profileItemArr == nil) && (deviceList?.count == 0 || deviceList == nil)) {
            self.tableview.setEmptyMessage("No record's found")
        } else {
            self.tableview.restore()
        }
        return profileItemArr?.count ?? deviceList?.count ?? 0
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        guard let cell =  tableView.dequeueReusableCell(withIdentifier: ListingProfileCell.reuseIdentifier, for: indexPath) as? ListingProfileCell else {return UITableViewCell()}
        if isProfile ?? false{
            cell.configureCell(profileItemArr?[indexPath.item].heading ?? "N/A", profileItemArr?[indexPath.item].data ?? "N/A",flag: true,item:indexPath.row)
        }
        else {
            cell.configureCell(deviceList?[indexPath.item].device_id ?? "N/A", deviceList?[indexPath.item].device_name ?? "N/A",flag: false,item:indexPath.row)
        }
        return cell
    }
    
    
}

extension PopUPTvVC:HandleError
{
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }

}
