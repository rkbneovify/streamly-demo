//
//  PopUPViewsVC.swift
//  Streamly
//
//  Created by Rohit Singh on 6/8/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class PopUPViewsVC: UIViewController {
    
    // MARK: - Outlets
    @IBOutlet weak var backView:UIView!
    @IBOutlet weak var backViewHeight: NSLayoutConstraint!
    @IBOutlet weak var txtview:UITextView!
        {
        didSet {
            txtview.delegate = self
        }
    }
    @IBOutlet weak var policiesView:UIView!
    @IBOutlet weak var okBtn:UIButton!
    
    // MARK: - propertis
    var item:Int?
    var contactInfo:ContactResponseData?
    var privacyData:PrivacyResponseData?
    var logOut:LogoutResponseData?
    lazy var viewModel: ContactDataViewModel = {
        return ContactDataViewModel()
    }()
    
    // MARK: - LifeCyle
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        self.initialViewSetup()
    }
    
    override func viewWillLayoutSubviews() {
        super.viewWillLayoutSubviews()
    }
    
    // MARK: - Helpers
    
    private func initialViewSetup()
    {
        switch item {
        case CustomPopUp.support.rawValue:
            self.supportViewdata()
        case CustomPopUp.policies.rawValue:
            self.policiesViewSetup()
        case CustomPopUp.logOut.rawValue:
            self.logOUTViewSetup()
        default:
            return
            
        }
    }
    
    private func logOUTViewSetup()
    {
        self.backViewHeight.constant = 488
        let customView = LogOUTView()
        customView.frame = backView.bounds
        customView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        customView.cornerRadius = 10.0
        customView.delegate = self
        backView.addSubview(customView)
    }
    
    private func supportViewSetup(_ data:ContactResponseData)
    {
        
        self.backViewHeight.constant = 574
        let customView = SupportView()
        customView.frame = backView.bounds
        customView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        customView.cornerRadius = 10.0
        customView.delegate = self
        customView.contactInfo = data
        backView.addSubview(customView)
    }
    
    private func supportViewdata()
    {
        guard let data = contactInfo else {return makeApiCall()}
        self.supportViewSetup(data)
    }
    
    private func policiesViewSetup(){
        self.makeAPiCallPrivacy()
        self.backView.isHidden = true
        self.policiesView.isHidden = false
        self.okBtn.setBtnProperties(self.okBtn)
        policiesView.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        policiesView.cornerRadius = 10.0
        txtview.isSelectable = true
        txtview.isUserInteractionEnabled = true
        txtview.panGestureRecognizer.allowedTouchTypes = [NSNumber(value: UITouch.TouchType.indirect.rawValue)]
    }
    
    private func makeApiCall()
    {
        self.showSpinner(onView: self.view)
        viewModel.delegate = self
        viewModel.getContactData()
        self.initVM()
        
        
        
    }
    private func makeAPiCallLogOut()
    {
        self.showSpinner(onView: self.view)
        viewModel.logOUT()
        viewModel.delegate = self
        self.initVMLogout()
    }
    
    private func makeAPiCallPrivacy()
    {
        self.showSpinner(onView: self.view)
        viewModel.getPrivacy()
        viewModel.delegate = self
        self.initVMPrivacy()
    }
    
    
    private func initVM()
    {
        viewModel.contactData = { contactModel in
            self.removeSpinner()
            self.contactInfo = contactModel
            guard let data = self.contactInfo else {
                return
            }
            self.supportViewSetup(data)
        }
        
    }
    
    private func initVMLogout()
    {
        viewModel.logoutdata = { logoutModel in
            self.removeSpinner()
            self.logOut = logoutModel
            guard let dataLogout = self.logOut else {
                return
            }
            if dataLogout.success ?? false
            {
                Constant.logOut()
            }
            
        }
    }
    
    private func initVMPrivacy()
    {
        viewModel.privacyResponseData = { privacyModel in
            self.removeSpinner()
            self.privacyData = privacyModel
            guard let dataprivacy = self.privacyData else {
                return
            }
            if dataprivacy.success ?? false
            {
                self.txtview.text = dataprivacy.data ?? ""
            }
            
        }
    }
    
    private func dissmissView()
    {
        self.dismiss(animated: true, completion: nil)
    }
    
    //MARK:- Actions
    @IBAction func actionBtns(_ sender: UIButton) {
        self.dissmissView()
    }
}

extension PopUPViewsVC:CustomViews{
    
    func callAction(_ tag: Int) {
        if tag == 22
        {
            self.dissmissView()
        }
        else if tag == 33 {
            self.makeAPiCallLogOut()
            
        }
        
    }
    
}

extension PopUPViewsVC:HandleError
{
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!, Please try again")
    }
    
}
extension  PopUPViewsVC:UITextViewDelegate
{
    func scrollViewDidScroll(_ scrollView: UIScrollView) {
        
        if (scrollView.contentOffset.y >= (scrollView.contentSize.height - scrollView.frame.size.height)) {
            //reach bottom
            self.okBtn.isHidden = false
        }
        
        if (scrollView.contentOffset.y < 0){
            //reach top
            self.okBtn.isHidden = true
        }
        
        if (scrollView.contentOffset.y >= 0 && scrollView.contentOffset.y < (scrollView.contentSize.height - scrollView.frame.size.height)){
            self.okBtn.isHidden = true
            //not top and not bottom
        }
    }
}

