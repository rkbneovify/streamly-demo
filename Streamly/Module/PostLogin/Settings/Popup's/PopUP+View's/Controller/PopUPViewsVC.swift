//
//  PopUPViewsVC.swift
//  Streamly
//
//  Created by Rohit Singh on 6/8/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class PopUPViewsVC: UIViewController {
    
    // MARK: Outlets
    @IBOutlet weak var termsView: UIView!
    @IBOutlet weak var vieww:GradientView!
    @IBOutlet weak var backView:UIView!
    @IBOutlet weak var backViewHeight: NSLayoutConstraint!
    @IBOutlet weak var txtview:UITextView!
        {
        didSet {
            txtview.delegate = self
        }
    }
    
    @IBOutlet weak var termsTextView: UITextView!
        {
        didSet {
            termsTextView.delegate = self
        }
    }
    @IBOutlet weak var goBackbtn: UIButton!
    @IBOutlet weak var acceptBtn: UIButton!
    @IBOutlet weak var policiesView:UIView!
    @IBOutlet weak var okBtn:UIButton!
    
    // MARK:  propertis
    var item:Int?
    var termsdata = ""
    var isfromLogin = false
    var contactInfo:ContactResponse?
    var privacyData:PrivacyResponseData?
    var logOut:LogoutResponseData?
    lazy var viewModel: ContactDataViewModel = {
        return ContactDataViewModel()
    }()
    
    // MARK:  LifeCyle
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        self.initialViewSetup()
    }
    
    override func viewWillLayoutSubviews() {
        super.viewWillLayoutSubviews()
    }
    
    // MARK:  Helpers
    
    private func initialViewSetup()
    {
        switch item {
        case CustomPopUp.support.rawValue:
            self.supportViewdata()
        case CustomPopUp.policies.rawValue:
            self.policiesViewSetup()
        case CustomPopUp.logOut.rawValue:
            self.logOUTViewSetup()
        case CustomPopUp.terms.rawValue:
            self.termsPopUpSetup()
        default:
            return
            
        }
    }
    
    private func logOUTViewSetup()
    {
        self.backViewHeight.constant = 488
        let customView = LogOUTView()
        customView.frame = backView.bounds
       // customView.backgroundColor = UIColor.gray.withAlphaComponent(0.9)
        customView.cornerRadius = 10.0
        customView.delegate = self
        backView.addSubview(customView)
    }
    
    private func supportViewSetup(_ data:ContactResponse)
    {
        
        self.backViewHeight.constant = 500
        if isfromLogin
            {
             //   self.view.backgroundColor = UIColor.black.withAlphaComponent(0.5)
        }
        self.view.layoutIfNeeded()
        let customView = SupportView()
        customView.frame = backView.bounds
      //  customView.backgroundColor = UIColor.gray.withAlphaComponent(0.9)
        customView.cornerRadius = 10.0
        customView.delegate = self
        customView.isflag = isfromLogin
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
        self.okBtn.setBtnProperties(self.okBtn, false)
       // policiesView.backgroundColor = UIColor.gray.withAlphaComponent(0.9)
        policiesView.cornerRadius = 10.0
        txtview.isSelectable = true
        txtview.isUserInteractionEnabled = true
        txtview.panGestureRecognizer.allowedTouchTypes = [NSNumber(value: UITouch.TouchType.indirect.rawValue)]
    }
    private func termsPopUpSetup(){
        self.vieww.firstColor = UIColor.firstGradientColor
        self.vieww.secondColor = UIColor.secondGradientColor
        if self.termsdata == ""
        {
            self.makeAPiCallTerms()
        }
        else {
            self.termsTextView.text = self.termsdata
        }
        self.backView.isHidden = true
        self.policiesView.isHidden = true
        self.termsView.isHidden = false
        self.goBackbtn.setBtnProperties(self.goBackbtn, true)
        //self.acceptBtn.setBtnProperties(self.acceptBtn, true)
        termsView.cornerRadius = 10.0
        termsTextView.isSelectable = true
        termsTextView.isUserInteractionEnabled = true
        termsTextView.panGestureRecognizer.allowedTouchTypes = [NSNumber(value: UITouch.TouchType.indirect.rawValue)]
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
    
    private func makeAPiCallTerms()
    {
        self.showSpinner(onView: self.view)
        viewModel.getTerms()
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
                self.item == 3 ? (self.txtview.text = dataprivacy.data ?? "") : (self.termsTextView.text = dataprivacy.data ?? "")
            }
            
        }
    }
    
    private func dissmissView()
    {
        self.dismiss(animated: true, completion: nil)
    }
    
    //MARK: Actions
    @IBAction func actionBtns(_ sender: UIButton) {
        if sender.tag == 88
        {
            // for open home while accept terms
        }
            
        else {
            self.dissmissView()
        }
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
    
    func successAPI() {
        self.removeSpinner()
    }
    
    func handleError(_ message: String, _ statusCode: Int) {
        self.removeSpinner()
        if statusCode == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
        } else if statusCode == 402 {
            showAlert(withMessage: "We're having some trouble with your current subscription. We'll try again, but in meantime you may want to update your subscription. For more help, please visit streamlytv.com or contact at https://streamlytv.com/helpandsupport.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
            return
        } else if statusCode == 404 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
            return
        }
        self.showSimpleAlertWithMessage("Something went wrong!, Please try again")
    }
    
}
extension  PopUPViewsVC:UITextViewDelegate
{
    func scrollViewDidScroll(_ scrollView: UIScrollView) {
        
        if (scrollView.contentOffset.y >= (scrollView.contentSize.height - scrollView.frame.size.height)) {
            //reach bottom
            if item == 5
            {
                // self.acceptBtn.isHidden = false
                self.goBackbtn.isHidden = false
            }
            else {
                self.okBtn.isHidden = false
            }
            
        }
        
        if (scrollView.contentOffset.y < 10){
            //reach top
            if item == 5
            {
                // self.acceptBtn.isHidden = true
                self.goBackbtn.isHidden = true
            }
            else {
                self.okBtn.isHidden = true
            }
        }
        
        if (scrollView.contentOffset.y >= 0 && scrollView.contentOffset.y < (scrollView.contentSize.height - scrollView.frame.size.height)){
            if item == 5
            {
                // self.acceptBtn.isHidden = true
                self.goBackbtn.isHidden = true
            }
            else {
                self.okBtn.isHidden = true
            }
            //not top and not bottom
        }
    }
}

extension PopUPViewsVC
{
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if goBackbtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                
                self.goBackbtn.layer.borderColor = UIColor.clear.cgColor
            }, completion: nil)
            
        }
        else {
            self.goBackbtn.layer.borderColor = UIColor.black.cgColor
        }
        //else if goBackbtn.isFocused{
        //               coordinator.addCoordinatedAnimations({ () -> Void in
        //             self.acceptBtn.layer.borderColor = UIColor.black.cgColor
        //             self.goBackbtn.layer.borderColor = UIColor.clear.cgColor
        //               }, completion: nil)
        //           }
        
    }
}
