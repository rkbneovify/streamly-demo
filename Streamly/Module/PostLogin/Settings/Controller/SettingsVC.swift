//
//  SettingsVC.swift
//  Streamly
//
//  Created by Rohit Singh on 6/2/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit



class SettingsVC: UIViewController {
    
    // MARK: Properties
    
    let itemArray = ["Account","Registered Devices","Support","Privacy & Policy","Sign out"]
    lazy var viewModel: ContactDataViewModel = {
        return ContactDataViewModel()
    }()
    var contactInfo:ContactResponse?
    
    // MARK: Outlets
    
    @IBOutlet weak var channellsBtn             :   UIButton!
    @IBOutlet weak var liveTVBtn                :   UIButton!
    @IBOutlet weak var collectionView           :   UICollectionView!
    
    
    
    // MARK: Lifecycles
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        
        // Do any additional setup after loading the view.
        self.initialSetup()
        ApiCall.getQRVisbility { data, status in
            if let model = data, let data: VisbilityInfo = DataParser.parseReponseToModel(model) {
                Utility.setBoolInUserDefaultData(value: data.visible ?? false, key: Constant.UserDefault.isVisibility)
                self.collectionView.reloadData()
            }
        } failure: { error, data, statusCode in
            print(error?.localizedDescription ?? "")
        }
        
    }
    
    override func viewDidLayoutSubviews() {
        
       // self.channellsBtn.setBtnProperties(self.channellsBtn, false)
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
        self.collectionView.layoutSubviews()
        self.collectionView.layoutIfNeeded()
    }

     // MARK: Actions

    @IBAction func backBtn(_ sende:UIButton)
    {
        self.navigationController?.popViewController(animated: true)
    }
    
    // MARK: Helpers
    
    private func initialSetup()
    {
       
        self.collectionView.delegate = self
        self.collectionView.dataSource = self
        DispatchQueue.global(qos: .background).async
            {
                self.makeAPICall()
                self.initVM()
        }
    }
    
    private func makeAPICall()
    {
        viewModel.getContactData()
    }
    
    private func initVM() {
        
        viewModel.contactData = { contactModel in
            self.contactInfo = contactModel
            
            
        }
    }
}

// MARK: DataSource

extension SettingsVC:UICollectionViewDataSource
{
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return itemArray.count
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        return collectionView.dequeueReusableCell(withReuseIdentifier: SettingsGridCell.reuseIdentifier, for: indexPath)
    }
    
    
}

// MARK: Delegate

extension SettingsVC:UICollectionViewDelegate, UICollectionViewDelegateFlowLayout {
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? SettingsGridCell else { fatalError("Expected to display a `SettingsGridCell`.") }
        cell.configureCell(itemname: itemArray[indexPath.item],tag: indexPath.item)
        cell.delegate = self
        
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        self.selectItem(indexPath.item)
        
    }
    
    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, sizeForItemAt indexPath: IndexPath) -> CGSize {
//        return indexPath.item == 1 || indexPath.item == 3 ? Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isVisibility) ? CGSize(width: 282, height: 280) : .zero : CGSize(width: 282, height: 280)
        return CGSize(width: 282, height: 280)
    }
    
    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, minimumInteritemSpacingForSectionAt section: Int) -> CGFloat {
        Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isVisibility) ? 0 : 10
    }
}

extension SettingsVC:Scroll {
    
    func scrollCollectionView(_ index: Int) {
    //    self.collectionView.scrollToItem(at: IndexPath(item: index, section: 0), at: [.centeredHorizontally, .centeredVertically], animated: true)
    }
    
}

extension SettingsVC{
    
    private func selectItem(_ itemNo:Int)
    {
        switch itemNo {
        case CustomPopUp.profile.rawValue:
            self.tablePopup(true)
        case CustomPopUp.registrdDevice.rawValue:
            openDeviceList()//self.tablePopup(false)
        case CustomPopUp.support.rawValue:
            self.viewPopups(itemNo)
        case CustomPopUp.policies.rawValue:
            self.viewPopups(itemNo)
        case CustomPopUp.logOut.rawValue:
            self.viewPopups(itemNo)
        default:
            return
            
        }
        
    }
    
    private func openDeviceList() {
        let vc = DeviceListViewController.instantiateFromAppStoryboard(appStoryboard: .Main)
        let searchController = UISearchController(searchResultsController: vc)
        searchController.searchResultsUpdater = vc
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Device ID or Name)", comment: "")
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        self.navigationController?.pushViewController(searchContainer, animated: true)
    }
    
    ///Helpers select item
    private func tablePopup(_ flag:Bool)
    {
        let popUPTvVC = PopUPTvVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        popUPTvVC.modalPresentationStyle = .overCurrentContext
        popUPTvVC.isProfile = flag
        self.present(popUPTvVC, animated:true, completion:nil)
    }
    
    private func viewPopups(_ item:Int)
    {
        let popUPViewsVC = PopUPViewsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        popUPViewsVC.modalPresentationStyle = .overCurrentContext
        popUPViewsVC.item = item
        popUPViewsVC.contactInfo = contactInfo
        self.present(popUPViewsVC, animated:true, completion:nil)
    }
}
extension SettingsVC{
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if channellsBtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in

                self.channellsBtn.layer.borderWidth = 3.0
                self.channellsBtn.layer.borderColor = UIColor.secondGradientColor.cgColor
            

            }, completion: nil)
        }
        else {
            self.channellsBtn.layer.borderWidth = 0.0
            self.channellsBtn.layer.borderColor = UIColor.clear.cgColor
        }
    }
}
