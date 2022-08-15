//
//  DeviceListViewController.swift
//  Streamly
//
//  Created by Aditya Joshi on 26/07/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit

protocol DeviceListDelegate: AnyObject {
    func sessionLogoutSuccess()
}

class DeviceListViewController: UIViewController {

    @IBOutlet var deviceCollectionView: UICollectionView!
    @IBOutlet var logOutButton: GradientBUtton!
    @IBOutlet var totalCountLabel: UILabel!
    
    private var viewModel = DeviceManageViewModel()
    
    var filterString = "" {
        didSet {
            // Return if the filter string hasn't changed.
            guard filterString != oldValue else { return }

            // Apply the filter or show all items if the filter string is empty.
            if filterString.isEmpty {
                filterList = deviceList
            } else {
                filterList = deviceList.filter({($0.personaldevicename?.lowercased().contains(filterString.lowercased()) ?? false) || ($0.deviceType?.lowercased().contains(filterString.lowercased()) ?? false) || ($0.hotelRoom?.lowercased().contains(filterString.lowercased()) ?? false)})
            }

            // Reload the collection view to reflect the changes.
            deviceCollectionView?.reloadData()
        }
    }
    var deviceList = [DeviceInfo]()
    var filterList = [DeviceInfo]()
    var isFromLogin = false
    
    weak var delegate: DeviceListDelegate?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialSetup()
    }

    

    private func initialSetup() {
        logOutButton.isHidden = true
        filterList = deviceList
        totalCountLabel.text = "Total connected Devices: \(deviceList.count)"
        deviceCollectionView.register(UINib(nibName: DeviceInfoGridCell.identifier, bundle: nil), forCellWithReuseIdentifier: DeviceInfoGridCell.identifier)
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
        //    self.collectionView.backgroundView = UIView()
        self.deviceCollectionView.reloadData()
        self.deviceCollectionView.layoutIfNeeded()
        self.deviceCollectionView.layoutSubviews()
        
        self.logOutButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.logOutButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.logOutButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.logOutButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.logOutButton.setTitleColor(UIColor.white, for: .focused)
        self.logOutButton.setTitleColor(UIColor.white, for: .highlighted)
        
        if #available(tvOS 14.0, *) {
            logOutButton.addAction(UIAction(handler: { _ in
                self.viewPopups()
            }), for: .primaryActionTriggered)
        } else {
            // Fallback on earlier versions
            logOutButton.addTarget(self, action: #selector(logOutAction(_:)), for: .primaryActionTriggered)
        }
        
        apicalling()
    }
    
    
    private func apicalling() {
        if !isFromLogin {
            viewModel.getRegistredDeviceData()
            viewModel.handleDeviceListResponse = { model in
                self.deviceList = model.data?.map({ device in
                    return DeviceInfo(connectedDevices: device.connected_devices, deviceID: device.device_id, deviceName: device.device_name, sessionID: device.session_id, personaldevicename: device.personaldevicename, personaldeviceid: device.personaldeviceid, deviceImg: device.device_img, deviceType: device.deviceType, hotelRoom: device.hotelRoom)
                }) ?? []
                self.deviceList = self.deviceList.filter({$0.personaldeviceid != Utility.retriveStringObject(key: Constant.UserDefault.mypersonaldeviceid)})
                self.filterList = self.deviceList
                self.totalCountLabel.text = "Total connected Devices: \(self.deviceList.count)"
                self.deviceCollectionView.reloadData()
            }
        }
    }
}

// MARK: - @objc and @IBaction
extension DeviceListViewController {
    @objc func logOutAction(_ sender: UIButton) {
        self.viewPopups()
    }
}

// MARK: - Handle Other controller
extension DeviceListViewController {
    private func viewPopups()
    {
        let popUPViewsVC = PopUPViewsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        popUPViewsVC.modalPresentationStyle = .overCurrentContext
        popUPViewsVC.item = 4
        self.present(popUPViewsVC, animated:true, completion:nil)
    }
}

// MARK: UICollectionViewDataSource

extension DeviceListViewController: UICollectionViewDataSource
{
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return filterList.count
        
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        // Dequeue a cell from the collection view.
        return collectionView.dequeueReusableCell(withReuseIdentifier: DeviceInfoGridCell.identifier, for: indexPath)
    }
    
    
}

extension DeviceListViewController: UICollectionViewDelegate {
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? DeviceInfoGridCell else { fatalError("Expected to display a `DeviceInfoGridCell`.") }

        // Configure the cell.
        cell.setupData(model: filterList[indexPath.item])
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        showAlert(title: appName, withMessage: "Are you sure want to logout this device?", setTwoButton: true, setFirstButtonTitle: "YES", setSecondButtonTitle: "NO") { _ in
            if let id = self.filterList[indexPath.item].sessionID {
                self.viewModel.logOutSession(id: id) { success, message in
                    if success {
                        if self.isFromLogin {
                            self.delegate?.sessionLogoutSuccess()
                            self.navigationController?.popViewController(animated: true)
                        } else {
                            self.viewModel.getRegistredDeviceData()
                        }
                    }
                }
            }
        }
    }
}

// MARK: - UISearchResultsUpdating
extension DeviceListViewController: UISearchResultsUpdating {
    func updateSearchResults(for searchController: UISearchController) {
        filterString = searchController.searchBar.text ?? ""
    }
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if logOutButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
               
            }, completion: nil)
        }else {
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.logOutButton.layer.borderColor = UIColor.gray.cgColor
            }, completion: nil)
            }
        }
}
