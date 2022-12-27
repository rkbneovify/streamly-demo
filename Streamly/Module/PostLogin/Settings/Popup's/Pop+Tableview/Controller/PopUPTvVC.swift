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
    @IBOutlet weak var alertBlurImage: UIImageView!
    @IBOutlet weak var deleteButton: UIButton!
    
    // MARK: - Properties
    var buildDate: Date {
    if let infoPath = Bundle.main.path(forResource: "Info", ofType: "plist"),
    let infoAttr = try? FileManager.default.attributesOfItem(atPath: infoPath),
    let infoDate = infoAttr[.modificationDate] as? Date {
      
        return infoDate
    }
        
        return Date()
    }
    // use this function to convert Date Format to String Format (remove time) and get desired format format
    func dateToString(input: Date) -> String {
        let date = Date()
        let dateFormatter = DateFormatter()
        dateFormatter.dateFormat = "MM/dd/yyyy"
        return dateFormatter.string(from: date)
        
    }
    

    
    //dateFormatter.dateFormat = "MM/dd/yyyy"
    
 
    

    
    
    var profileItemArr:[(heading:String,data:String)]?
    var isProfile:Bool?
    {
        didSet{
            if isProfile ?? false {
                let seconds = TimeZone.current.secondsFromGMT()
                let minutes = abs(seconds/60) % 60
                let hours = seconds/3600
//                let tz = String(format: "%.2d:%.2d", hours, minutes)
                let tz = TimeZone.current.localizedName(for: .standard, locale: .current)
                profileItemArr  = [("Username:", "\(String(describing: Utility.retriveStringObject(key: Constant.UserDefault.userName)))"),("Email:", "\(String(describing: Utility.retriveStringObject(key: Constant.UserDefault.email)))"),("Version:","\(GEVersionMonitor.shared.formattedVersion)"),("Build Date:","\(dateToString(input: buildDate))"), ("Home TimeZone:","\(Utility.retriveStringObject(key: Constant.UserDefault.timezone) )"), ("Current TimeZone:","\(String(describing: tz ?? ""))")]
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
        
      //  self.backView.alpha = 0.8
        self.okButton.setBtnProperties(self.okButton, false)
        self.deleteButton.setBtnProperties(self.deleteButton, false)

    }

    // MARK: - Action method
    @IBAction func okAction(_ sender:UIButton)
    {
        self.dismiss(animated: true, completion: nil)

    }
    
    @IBAction func deleteAction(_ sender: UIButton) {
        showAlert(title: appName, withMessage: "Are you sure you want to delete this account?", setTwoButton: true, setFirstButtonTitle: "YES", setSecondButtonTitle: "NO") { _ in
            self.viewModel.deleteUserFromSource { [weak self] success, message in
                guard let weak = self else { return }
                if success {
                    weak.showToast(message: message) {
                        Constant.logOut()
                        Switcher.updateRootVC()
                    }
                } else {
                    weak.showSimpleAlertWithMessage(message)
                }
            }
        }
    }

    // MARK: - Helpers

    private func initialSetup()
    {
        deleteButton.isHidden = !(isProfile ?? false)
        if !(isProfile ?? true)
        {
            self.showSpinner(onView: self.view)
            self.makeApiCall()
            self.initVM()
        }
       // self.tableview.delegate =  self
        self.tableview.dataSource  = self
        if !(isProfile ?? false) {
            self.tableview.rowHeight = 300
        }
        self.tableview.register(UINib(nibName: DeviceRegisterCell.identifier, bundle: nil), forCellReuseIdentifier: DeviceRegisterCell.identifier)
        self.tableview.reloadData()
        
    }
    
    func makeApiCall(){
        viewModel.delegate = self
        viewModel.getRegistredDeviceData()

    }
    
    private func initVM() {
        
        viewModel.registredDevice = { contactModel in
            self.deviceList = contactModel.data
            //debugPrint("")
            self.removeSpinner()

            
        }
        
    }
    
    
}

extension PopUPTvVC:UITableViewDataSource
{
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {

        if ((profileItemArr?.isEmpty ?? true || profileItemArr == nil) && (deviceList?.isEmpty ?? true || deviceList == nil)) {
            self.tableview.setEmptyMessage("No record's found")
        } else {
            self.tableview.restore()
        }
        return profileItemArr?.count ?? deviceList?.count ?? 0
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        if isProfile ?? false {
            guard let cell =  tableView.dequeueReusableCell(withIdentifier: ListingProfileCell.reuseIdentifier, for: indexPath) as? ListingProfileCell else {return UITableViewCell()}
            cell.backgroundColor = UIColor.clear
            cell.contentView.backgroundColor = UIColor.clear
            cell.configureCell(profileItemArr?[indexPath.item].heading ?? "N/A", profileItemArr?[indexPath.item].data ?? "N/A",flag: true,item:indexPath.row)
            return cell
        } else {
            guard let cell = tableview.dequeueReusableCell(withIdentifier: DeviceRegisterCell.identifier, for: indexPath) as? DeviceRegisterCell else { return UITableViewCell() }
            cell.backgroundColor = UIColor.clear
            cell.contentView.backgroundColor = UIColor.clear
            cell.setupData(deviceList?[indexPath.item].device_id ?? "N/A", deviceList?[indexPath.item].device_name ?? "N/A",image: deviceList?[indexPath.item].device_img ?? "N/A",sessionId: deviceList?[indexPath.item].personaldevicename ?? "N/A")
            return cell
        }
    }
}

extension PopUPTvVC:HandleError
{
    
    func successAPI() {
        self.removeSpinner()
    }
    
    func handleError(_ message: String, _ statusCode: Int) {
        if statusCode == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
        } else if statusCode == 402 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
            return
        } else if statusCode == 404 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
            return
        }
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }
}
